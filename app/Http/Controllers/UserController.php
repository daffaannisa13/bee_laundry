<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List semua user
    public function index()
{
    $users = User::orderBy('id', 'desc')->paginate(10);
    return view('users.index', compact('users'));
}

 public function search(Request $request)
    {
        $search = $request->search;

        $users = User::query();
        if($search){
            $users->where('name','like',"%$search%")
                  ->orWhere('email','like',"%$search%");
        }

        $users = $users->orderBy('id','desc')
                       ->paginate(10)
                       ->appends(['search'=>$search])
                       ->withPath(route('users.search'));

        // HTML tabel + header
        $htmlUsers = '<div class="orders-table-container">
            <div class="orders-table">
                <div class="table-header">
                    <span>User ID</span>
                    <span>Name</span>
                    <span>Email</span>
                    <span>Role</span>
                    <span>Action</span>
                </div>';

        foreach($users as $user){
            $htmlUsers .= '<div class="table-row">
                <span>#'.$user->id.'</span>
                <span>'.htmlspecialchars($user->name, ENT_QUOTES).'</span>
                <span>'.htmlspecialchars($user->email, ENT_QUOTES).'</span>
                <span>'.$user->role.'</span>
                <span class="action-buttons">
                    <button class="btn btn-edit"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-delete"><i class="fa-solid fa-trash"></i></button>
                    <button class="btn btn-detail"><i class="fa-solid fa-info-circle"></i></button>
                </span>
            </div>';
        }
        $htmlUsers .= '</div></div>';

        // Pagination custom
        $pagination = '<ul class="pagination">';

        // Prev
        if($users->onFirstPage()) $pagination .= '<li class="disabled"><a href="#">Prev</a></li>';
        else $pagination .= '<li><a href="'.$users->previousPageUrl().'&search='.$search.'">Prev</a></li>';

        // Nomor halaman
        foreach($users->getUrlRange(1,$users->lastPage()) as $page => $url){
            $active = $page == $users->currentPage() ? 'active' : '';
            $pagination .= '<li class="'.$active.'">
                <a href="'.$url.'&search='.$search.'">'.$page.'</a>
            </li>';
        }

        // Next
        if($users->hasMorePages()) $pagination .= '<li><a href="'.$users->nextPageUrl().'&search='.$search.'">Next</a></li>';
        else $pagination .= '<li class="disabled"><a href="#">Next</a></li>';

        $pagination .= '</ul>';

        return response()->json([
            'data' => $htmlUsers,
            'pagination' => $pagination
        ]);
    }

    // Form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'address'  => 'required|string|max:255',
        'phone'    => 'required|string|max:20',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:8|max:32',
        'role'     => 'nullable|in:user,admin'
    ]);

    // Kalau bukan admin → otomatis user
    if(auth()->user()->role !== 'admin'){
        $request->merge(['role' => 'user']);
    }

    User::create([
        'name'     => $request->name,
        'address'  => $request->address,
        'phone'    => $request->phone,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $request->role ?? 'user',
    ]);

    return response()->json(['success' => true]);
}




    // Edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
  public function update(Request $request, $id)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone'   => 'required|string|max:20',
        'email'   => 'required|email|unique:users,email,' . $id,
        'role'    => 'nullable|in:user,admin',
    ]);

    $user = User::findOrFail($id);

    // Kalau bukan admin, role tidak boleh diganti
    if(auth()->user()->role !== 'admin') {
        $request->merge(['role' => $user->role]); // tetap role lama
    }

    $user->update([
        'name'    => $request->name,
        'address' => $request->address,
        'phone'   => $request->phone,
        'email'   => $request->email,
        'role'    => $request->role,
    ]);

    return response()->json(['success' => true]);
}

public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    
    // Hapus user
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'User deleted');
    }
}
