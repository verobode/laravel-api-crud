<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:Python,Javascript'
        ]);
        if ($validator -> fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errores' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
            # code...
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        if (!$student) {
            return response()->json([
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ], 500);
        }

        return response()->json([
            'student' => $student,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404 
            ];
            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        //Si no hay un estudiante, muestra el mensaje
        if (!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404 
            ];
            return response()->json($data, 404);
        }
        //Si encuentra al estudiante, ejecuta el método delete
        $student->delete();
        //y muestra el mensaje
        $data = [
            'message' => 'Estudiante eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student,email,',
            'phone' => 'required|digits:10',
            'language' => 'required|in:Python,Javascript'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errores' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // return response()->json($request->all(), 200);

        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|unique:student,email,',
            'phone' => 'digits:10',
            'language' => 'in:Python,Javascript'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errores' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')){
            $student->name = $request->name;
        }

        if ($request->has('email')){
            $student->email = $request->email;
        }

        if ($request->has('phone')){
            $student->phone = $request->phone;
        }

        if ($request->has('language')){
            $student->language = $request->language;
        }

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


}





// Importar en api.php 