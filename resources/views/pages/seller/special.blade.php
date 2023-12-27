@extends('layouts.cotizador')

@section('content')
    <div class="mx-auto">
        {{-- @if(session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif --}}
        <div class="flex justify-between mx-20 mt-20">
            <h3 class="text-2xl font-bold "> Solicitudes especiales</h3>

            <div>
                <!-- Modal toggle -->
                <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Crear nueva solicitud
                </button>
  
                <!-- Main modal -->
                <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Crear nueva solicitud
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>

                            <form action="{{ route('specialStorage') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                
                                <div class="p-4 md:p-5 space-y-4">

                                    <div class="mb-4">
                                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                                        <textarea name="description" id="description" rows="4" class="w-full p-2 border rounded-md" required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="image_reference" class="block text-gray-700 text-sm font-bold mb-2">Referencia de Imagen</label>
                                        <input type="file" name="image_reference" id="image_reference" class="w-full p-2 border rounded-md" >
                                    </div>

                                    <div class="mb-4">
                                        <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Archivo</label>
                                        <input type="file" name="file" id="file" class="w-full p-2 border rounded-md" >
                                    </div>

                                </div>

                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Aceptar</button>
                                    <button data-modal-hide="default-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancelar</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
  
            </div>
        </div>

        <div class="w-full mx-20 mt-5">
            @if( count($special_requests) == 0 )

                <div>
                    <p class="text-lg text-black">No hay solicitudes especiales</p>
                </div>

            @else

                <table  style="width:90%;">

                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th style="width: 5%;" class="p-4">#</th>
                            <th style="width: 35%;">Descripción</th>
                            <th style="width: 15%;">Imagen de referencia</th>
                            <th style="width: 15%;">Archivo</th>
                            <th style="width: 15%;">Fecha</th>
                            <th style="width: 15%;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">

                        @foreach($special_requests as $special_request)

                            <tr class="border">
                                <td class="m-8">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $special_request->description }}
                                </td>

                                <td class="flex justify-center items-center"> 

                                    @if($special_request->image_reference !=null || $special_request->image_reference != '' )
                                        <img src="{{ asset($special_request->image_reference) }}" alt="Imagen" width="100" height="100" class="" >
                                    @else
                                        <p>Sin imagen</p>
                                    @endif

                                </td>
                                    
                                <td>
                                    @if($special_request->file !=null ||$special_request->file != '' )
                                        <a href="{{$special_request->file}}" class="text-blue-500 font-bold no-underline" target="__blank">Ver archivo</a>
                                    @else
                                        <p>Sin archivo</p>
                                    @endif
                                </td>

                                <td>
                                    {{ $special_request->updated_at->format('d-m-Y H:s') }}
                                </td>

                                <td>
                                    <!-- Modal toggle -->
                                    <button data-modal-target="edit-modal-{{$special_request->id}}" data-modal-toggle="edit-modal-{{$special_request->id}}" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ml-16" type="button">
                                        Editar
                                    </button>
                    
                                    <!-- Main modal -->
                                    <div id="edit-modal-{{$special_request->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <!-- Modal header -->
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-start">
                                                        Editar solicitud
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('specialUpdate') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')
                                                    
                                                    <div class="p-4 md:p-5 space-y-4">
                                                        <input type="text" name="id" id="id" class="w-full p-2 border rounded-md" value="{{$special_request->id}}" hidden>

                                                        <div class="mb-4">
                                                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2 text-start">Descripción</label>
                                                            <textarea name="description" id="description" rows="4" class="w-full p-2 border rounded-md" required> {{ $special_request->description }}</textarea>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="image_reference" class="block text-gray-700 text-sm font-bold mb-2 text-start">Referencia de Imagen</label>
                                                            <input type="file" name="image_reference" id="image_reference" class="w-full p-2 border rounded-md" >
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="file" class="block text-gray-700 text-sm font-bold mb-2 text-start">Archivo</label>
                                                            <input type="file" name="file" id="file" class="w-full p-2 border rounded-md" >
                                                        </div>

                                                    </div>

                                                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar</button>
                                                        <button data-modal-hide="edit-modal-{{$special_request->id}}"  type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancelar</button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                            </tr>

                        @endforeach
                         
                    </tbody>

              </table>
                
              <div>
                {{ $special_requests->links() }}
              </div>
            @endif

        </div>

       

        

        
    </div>
@endsection
