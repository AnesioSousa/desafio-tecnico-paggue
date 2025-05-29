<?php
// src/app/Http/Controllers/ProdutorController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProducerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // only admins may CRUD producers
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::where('role', 'producer')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'cpf_cnpj' => 'nullable|string|unique:users',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'cpf_cnpj' => $data['cpf_cnpj'] ?? null,
            'role' => 'producer',  //legacy

        ]);

        $user->assignRole('producer');

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $producer)
    {
        abort_if($producer->role !== 'producer', 404);
        return $producer;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $produtor)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $producer)
    {
        abort_if($producer->role !== 'producer', 404);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $producer->id,
            'password' => 'sometimes|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'cpf_cnpj' => 'nullable|string|unique:users,cpf_cnpj,' . $producer->id,
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $producer->update($data);
        return $producer;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $producer)
    {
        abort_if($producer->role !== 'producer', 404);
        $producer->delete();
        return response()->noContent();
    }
}
