<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pwm;
use App\Models\Pdm;
use App\Models\Pcm;
use App\Models\Prm;

class UserController extends Controller
{
    private function allowedRoles(): array
    {
        return match (Auth::user()->role) {
            'super_admin' => ['admin_pp','admin_pwm','admin_pdm','admin_pcm','admin_prm','admin_masjid'],
            'admin_pwm'   => ['admin_pdm','admin_pcm','admin_prm','admin_masjid'],
            'admin_pdm'   => ['admin_pcm','admin_prm','admin_masjid'],
            'admin_pcm'   => ['admin_prm','admin_masjid'],
            'admin_prm'   => ['admin_masjid'],
            default       => [],
        };
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_if($user->isAdminPP() || $user->isAdminMasjid(), 403);

        $query = User::query()
            ->whereIn('role', $this->allowedRoles())
            ->with(['pwm','pdm','pcm','prm']);

        // Scope wilayah sesuai level
        if ($user->isAdminPWM()) {
            $query->where(function($q) use ($user) {
                $q->where('pwm_id', $user->pwm_id)
                  ->orWhereHas('pdm', fn($x) => $x->where('pwm_id', $user->pwm_id))
                  ->orWhereHas('pcm.pdm', fn($x) => $x->where('pwm_id', $user->pwm_id))
                  ->orWhereHas('prm.pcm.pdm', fn($x) => $x->where('pwm_id', $user->pwm_id));
            });
        } elseif ($user->isAdminPDM()) {
            $query->where(function($q) use ($user) {
                $q->where('pdm_id', $user->pdm_id)
                  ->orWhereHas('pcm', fn($x) => $x->where('pdm_id', $user->pdm_id))
                  ->orWhereHas('prm.pcm', fn($x) => $x->where('pdm_id', $user->pdm_id));
            });
        } elseif ($user->isAdminPCM()) {
            $query->where(function($q) use ($user) {
                $q->where('pcm_id', $user->pcm_id)
                  ->orWhereHas('prm', fn($x) => $x->where('pcm_id', $user->pcm_id));
            });
        } elseif ($user->isAdminPRM()) {
            $query->where('prm_id', $user->prm_id);
        }

        // Filter cari
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn($q) => $q->where('name','like',"%$cari%")->orWhere('username','like',"%$cari%"));
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter status
        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        // Filter wilayah cascading — PRM lebih spesifik didahulukan
        if ($request->filled('prm_id')) {
            $query->where('prm_id', $request->prm_id);
        } elseif ($request->filled('pcm_id')) {
            $query->where(fn($q) => $q->where('pcm_id', $request->pcm_id)
                ->orWhereHas('prm', fn($x) => $x->where('pcm_id', $request->pcm_id)));
        } elseif ($request->filled('pdm_id')) {
            $query->where(fn($q) => $q->where('pdm_id', $request->pdm_id)
                ->orWhereHas('pcm', fn($x) => $x->where('pdm_id', $request->pdm_id))
                ->orWhereHas('prm.pcm', fn($x) => $x->where('pdm_id', $request->pdm_id)));
        } elseif ($request->filled('pwm_id')) {
            $query->where(fn($q) => $q->where('pwm_id', $request->pwm_id)
                ->orWhereHas('pdm', fn($x) => $x->where('pwm_id', $request->pwm_id))
                ->orWhereHas('pcm.pdm', fn($x) => $x->where('pwm_id', $request->pwm_id))
                ->orWhereHas('prm.pcm.pdm', fn($x) => $x->where('pwm_id', $request->pwm_id)));
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(25)->withQueryString();

        // Data untuk filter wilayah (hanya superadmin/admin_pp)
        $pwmList = collect();
        $pdmList = collect();
        $pcmList = collect();
        $prmList = collect();

        if ($user->isSuperAdmin() || $user->role === 'admin_pp') {
            $pwmList = Pwm::orderBy('nama')->get(['id','nama']);

            if ($request->filled('pwm_id')) {
                $pdmList = Pdm::where('pwm_id', $request->pwm_id)->orderBy('nama')->get(['id','nama']);
            }
            if ($request->filled('pdm_id')) {
                $pcmList = Pcm::where('pdm_id', $request->pdm_id)->orderBy('nama')->get(['id','nama']);
            }
            if ($request->filled('pcm_id')) {
                $prmList = Prm::where('pcm_id', $request->pcm_id)->orderBy('nama')->get(['id','nama']);
            }
        }

        return view('user.index', compact('users','user','pwmList','pdmList','pcmList','prmList'));
    }

    public function create()
    {
        $user         = Auth::user();
        $allowedRoles = $this->allowedRoles();
        abort_if(empty($allowedRoles), 403);
        $pwmList = $user->isSuperAdmin() ? Pwm::where('aktif',true)->orderBy('nama')->get() : collect();
        return view('user.create', compact('user','allowedRoles','pwmList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if(empty($this->allowedRoles()), 403);
        abort_unless(in_array($request->role, $this->allowedRoles()), 403);

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users,username',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|string',
            'pwm_id'    => 'nullable|exists:pwm,id',
            'pdm_id'    => 'nullable|exists:pdm,id',
            'pcm_id'    => 'nullable|exists:pcm,id',
            'prm_id'    => 'nullable|exists:prm,id',
            'masjid_id' => 'nullable|exists:masjid,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['aktif']    = true;
        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $authUser     = Auth::user();
        $allowedRoles = $this->allowedRoles();
        abort_unless(in_array($user->role, $allowedRoles) || $authUser->isSuperAdmin(), 403);
        return view('user.edit', compact('user','authUser','allowedRoles'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        abort_unless(in_array($user->role, $this->allowedRoles()) || $authUser->isSuperAdmin(), 403);

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email,'.$user->id,
            'aktif'    => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['aktif'] = $request->boolean('aktif');
        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();
        abort_unless(in_array($user->role, $this->allowedRoles()) || $authUser->isSuperAdmin(), 403);
        abort_if($user->id === $authUser->id, 403, 'Tidak dapat menghapus akun sendiri.');
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}