<div class="w-full ">
    <h3 class="text-2xl font-semibold mx-20 mt-20">Mis compras</h3>

    <div class="w-full px-20 mt-10">
        <table class="w-full table-auto">
            <thead>
              <tr class="bg-blue-900 text-white">
                <th style="width: 10%;" class="p-2">#</th>
                <th style="width: 20%;">Poducto</th>
                <th style="width: 25%;">Descripción</th>
                <th style="width: 10%;">Cantidad</th>
                <th style="width: 10%;">Total</th>
                <th style="width: 10%;">Fecha de pedido</th>
                <th style="width: 10%;">Status</th>
                @role('seller')
                  <th style="width: 5%;"></th>   
                @endrole
              </tr>
            </thead>
            <tbody>
              @foreach ($shoppings  as $shopping)
                <tr class="border border-gray-300">
                    <td class="text-center py-5 px-6">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      @php
                        $product = json_decode($shopping->products[0]->product, true);
                      @endphp
                      
                      {{ $product['name'] }}
                    </td>
                    <td>
                      {{ $product['description'] }}
                    </td>
                    <td class="text-center"> 
                      {{ $shopping->products[0]->cantidad }}
                    </td>
                    <td class="text-center">
                      {{ number_format($shopping->products[0]->precio_total, 2, '.', ',') }}
                    </td>
                    <td class="text-center">
                      {{ $shopping->products[0]->updated_at->format('d-m-Y') }}
                    </td>
                    <td class="text-center">
                      @switch($shopping->status)
                        @case(0)
                          <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Pendiente</span>
                            @break
                        @case(1)
                          <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">En proceso</span>
                            @break
                        @case(2)
                          <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Enviado</span>
                            @break
                        @case(3)
                          <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Entregado</span>
                            @break
                        @default
                            
                      @endswitch
                    </td>
                    @role('seller')
                      <td>
                        <!-- Modal toggle -->
                        <button data-modal-target="edit-modal-{{$shopping->id}}" data-modal-toggle="edit-modal-{{$shopping->id}}" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mx-2" type="button">
                          Cambiar status
                        </button>
      
                        <!-- Main modal -->
                        <div id="edit-modal-{{$shopping->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 ">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-start">
                                            Cambiar status
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('compras.status') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        
                                        <div class="p-4 md:p-5 space-y-4">

                                          <input type="text" value="{{$shopping->id}}" name="shopping_id" hidden>
                                          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                          <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="0" {{ $shopping->status == 0 ? 'selected' : '' }}>Pendiente</option>
                                            <option value="1" {{ $shopping->status == 1 ? 'selected' : '' }}>En proceso</option>
                                            <option value="2" {{ $shopping->status == 2 ? 'selected' : '' }}>Enviado</option>
                                            <option value="3" {{ $shopping->status == 3 ? 'selected' : '' }}>Entregado</option>
                                          </select>
                                          
                                        </div>

                                        <div class="flex items-center p-4 md:p-5 ">
                                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar</button>
                                            <button data-modal-hide="edit-modal-{{$shopping->id}}"  type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancelar</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                      </td>
                    @endrole
                </tr>
              @endforeach
            </tbody>
        </table>
        <style>
          .pagination{
            display: flex;
          }

          .page-link{
            padding: 10px;
          }
        </style>
        <div class="flex w-full justify-end mt-5">
          {{ $shoppings->links() }}
        </div>

    </div>
    
    
</div>
