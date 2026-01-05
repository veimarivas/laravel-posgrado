<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Convenio;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ConveniosController extends Controller
{
    public function conveniosListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Convenio::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('sigla', 'like', "%{$search}%");
            });
        }

        $convenios = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.convenios.partials.table-body', compact('convenios'))->render(),
                'pagination' => $convenios->links('pagination::bootstrap-5')->toHtml(),
                'total' => $convenios->total(),
                'from' => $convenios->firstItem(),
                'to' => $convenios->lastItem()
            ]);
        }

        return view('admin.convenios.listar', compact('convenios'));
    }

    public function conveniosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:convenios,nombre',
            'sigla' => 'nullable|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $convenio = new Convenio();
        $convenio->nombre = $request->nombre;
        $convenio->sigla = $request->sigla;

        // Subir imagen si se proporciona
        if ($request->hasFile('imagen')) {
            $imagenPath = $this->subirImagen($request->file('imagen'));
            $convenio->imagen = $imagenPath;
        }

        $convenio->save();

        return response()->json([
            'success' => true,
            'msg' => 'Convenio registrado correctamente.',
            'convenio' => $convenio
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Convenio::where('nombre', $nombre);

        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
        ]);

        $nombre = trim($request->input('nombre'));
        $id = $request->input('id');

        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $exists = Convenio::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function conveniosModificar(Request $request)
    {
        \Log::info('Datos recibidos para modificar:', $request->all());
        \Log::info('Archivos recibidos:', $request->hasFile('imagen') ? ['imagen' => 'presente'] : ['imagen' => 'no presente']);

        $request->validate([
            'id' => 'required|exists:convenios,id',
            'nombre' => 'required|unique:convenios,nombre,' . $request->id,
            'sigla' => 'nullable|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $convenio = Convenio::findOrFail($request->id);

        // Verificar si el nombre realmente cambió
        if ($convenio->nombre !== $request->nombre) {
            $existe = Convenio::where('nombre', $request->nombre)
                ->where('id', '!=', $request->id)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El nombre del convenio ya existe.'
                ], 422);
            }
        }

        $convenio->nombre = $request->nombre;
        $convenio->sigla = $request->sigla;

        // Subir imagen si se proporciona
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($convenio->imagen && file_exists(public_path($convenio->imagen))) {
                unlink(public_path($convenio->imagen));
            }

            $imagenPath = $this->subirImagen($request->file('imagen'));
            $convenio->imagen = $imagenPath;
        }

        $convenio->save();

        return response()->json([
            'success' => true,
            'msg' => 'Convenio actualizado correctamente.'
        ]);
    }

    public function conveniosEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:convenios,id'
        ]);

        $convenio = Convenio::findOrFail($request->id);

        // Eliminar imagen si existe
        if ($convenio->imagen && file_exists(public_path($convenio->imagen))) {
            unlink(public_path($convenio->imagen));
        }

        $convenio->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Convenio eliminado correctamente.'
        ]);
    }

    // Método para subir imágenes
    private function subirImagen($file)
    {
        $filename = 'convenio_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'upload/convenios/' . $filename;

        // Crear directorio si no existe
        if (!file_exists(public_path('upload/convenios'))) {
            mkdir(public_path('upload/convenios'), 0755, true);
        }

        // Redimensionar y guardar imagen
        $image = Image::make($file)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save(public_path($path), 85);

        return $path;
    }

    // Método para obtener datos de un convenio (si necesitas una vista individual)
    public function ver($id)
    {
        $convenio = Convenio::findOrFail($id);
        return view('admin.convenios.ver', compact('convenio'));
    }
}
