<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = UserModel::with(['hotel'])
            ->get();
        if ($user->isNotEmpty()) {
            return UserResource::collection($user);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào người dùng'
                ],
                200
            );
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'UserName' => 'required|string|max:15|unique:User,UserName',
                'FullName' => 'required|string|max:30',
                'Password' => 'required|string|min:6|max:25',
                // 'UserStatus' => 'in:0,1',
                'Role' => 'required|string|max:64',
                'HotelId' => 'required|exists:Hotel,HotelId'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'UserName.required' => 'Tên đăng nhập không được để trống.',
                'UserName.string' => 'Tên đăng nhập phải là một chuỗi ký tự.',
                'UserName.max' => 'Tên đăng nhập không được dài quá 15 ký tự.',
                'UserName.unique' => 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.',

                'FullName.required' => 'Họ và tên không được để trống.',
                'FullName.string' => 'Họ và tên phải là một chuỗi ký tự.',
                'FullName.max' => 'Họ và tên không được dài quá 30 ký tự.',

                'Password.required' => 'Mật khẩu không được để trống.',
                'Password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
                'Password.max' => 'Mật khẩu không được dài quá 25 ký tự.',
                'Password.min' => 'Mật khẩu không được ngắn quá 6 ký tự.',

                // 'UserStatus.in' => 'Trạng thái nhân viên chỉ có thể là 0 (nghỉ làm) hoặc 1 (đang làm).',

                'Role.required' => 'Vai trò không được để trống.',
                'Role.string' => 'Vai trò phải là một chuỗi ký tự.',
                'Role.max' => 'Vai trò không được dài quá 64 ký tự.',

                'HotelId.required' => 'ID khách sạn không được để trống.',
                'HotelId.exists' => 'ID khách sạn không tồn tại.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Đăng ký thất bại',
                'error' => $validator->errors(),
            ], 422);
        }

        $user = UserModel::create(
            [
                'UserName' => $request->UserName,
                'FullName' => $request->FullName,
                'Password' => bcrypt($request->Password),
                // 'UserStatus' => $request->UserStatus,
                'Role' => $request->Role,
                'HotelId' => $request->HotelId,
            ]
        );

        return response()->json([
            'message' => 'Đăng ký thành công',
            'data' => new UserResource($user)
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'UserName' => 'required|string|max:255',
                'Password' => 'required|string|min:6|max:25',
            ],
            [
                'UserName.required' => 'Tên đăng nhập không được để trống.',
                'UserName.string' => 'Tên đăng nhập phải là một chuỗi ký tự.',
                'UserName.max' => 'Tên đăng nhập không được dài quá 15 ký tự.',
                'Password.required' => 'Mật khẩu không được để trống.',
                'Password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
                'Password.max' => 'Mật khẩu không được dài quá 25 ký tự.',
                'Password.min' => 'Mật khẩu không được ngắn quá 6 ký tự.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = UserModel::where('UserName', $request->UserName)->first();

        if (!$user || !Hash::check($request->Password, $user->Password)) {
            return response()->json([
                'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.',
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }
}
