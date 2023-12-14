<div>
    <div class="z-50 fixed bottom-0 right-0 p-4 rounded-md">
        <div class="hidden transition-all" id="soporte" wire:ignore.self>
            <div class="flex flex-col justify-between bg-white shadow-2xl rounded-md" style="width: 350px; height: 550px">
                <div class="bg-primary h-16 pt-2 text-white flex justify-between items-center shadow-md p-2 rounded-md"
                    style="width: 350px">
                    <div class="my-3 text-green-100 font-bold text-lg tracking-wide">Soporte </div>
                    <div onclick="hideChat()" class="hover:cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                    </div>
                </div>
                <div class="overflow-y-auto h-full" id="chatWithSeller">
                    @if (count($messages) <= 0)
                        <p class="p-4 text-center">Da el primer paso.</p>
                    @endif
                    @foreach ($messages as $item)
                        @if ($item->receiver_id == auth()->user()->id)
                            <div class="clearfix">
                                <div class="bg-gray-200 w-3/4 mx-4 my-2 p-2 rounded-lg flex flex-wrap justify-between">
                                    <span class="text-sm">{{ $item->user->name }}</span>
                                    <p class="m-0 p-0 w-full break-words">
                                        {{ json_decode($item->message)->data }}
                                    </p>
                                    
                                    <span
                                        class="text-xs w-full text-right">{{ $item->is_read ==1? 'Leido:  '.  $item->updated_at->format('H:i')  :  'Enviado:  '. $item->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="clearfix">
                                <div
                                    class="bg-primary text-white float-right w-3/4 mx-4 my-2 p-2 rounded-lg clearfix flex flex-wrap justify-between">
                                    <span class="text-sm">Tú</span>
                                    <p class="m-0 p-0 w-full break-words">
                                        {{ json_decode($item->message)->data }}
                                    </p>
                                    <span
                                        class="text-xs w-full text-right">{{ $item->is_read ==1? 'Leido:  '.  $item->updated_at->format('H:i')  :  'Enviado:  '. $item->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="flex justify-between bg-white" style="bottom: 0px; width: 350px; rounded-md">
                    <textarea
                        class="flex-grow m-2 py-2 px-4 mr-1 rounded-full border border-gray-300 bg-gray-200 h-auto resize-y overflow-y-auto"
                        rows="1" placeholder="Message..." style="outline: none;" wire:keydown.enter='sendMessage'
                        wire:model='message'>
                    </textarea>
                    <button class="m-2" style="outline: none;" wire:click='sendMessage'>
                        <svg class="svg-inline--fa text-primary-dark fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2"
                            aria-hidden="true" focusable="false" data-prefix="fas" data-icon="paper-plane"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- <div class="block transition-all" id="buttonSoporte" wire:ignore.self>
            <button onclick="showChat()"
                class="bg-primary hover:bg-primary-dark text-white font-bold rounded-full shadow-lg w-14 h-14 flex items-center justify-center">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                </div>
            </button>
        </div> -->
        <a href="https://api.whatsapp.com/send?phone=525566282051" class="fixed bottom-4 right-4 p-4 bg-green-500 text-white rounded-full shadow-lg">
                <svg width="40px" height="40px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16 31C23.732 31 30 24.732 30 17C30 9.26801 23.732 3 16 3C8.26801 3 2 9.26801 2 17C2 19.5109 2.661 21.8674 3.81847 23.905L2 31L9.31486 29.3038C11.3014 30.3854 13.5789 31 16 31ZM16 28.8462C22.5425 28.8462 27.8462 23.5425 27.8462 17C27.8462 10.4576 22.5425 5.15385 16 5.15385C9.45755 5.15385 4.15385 10.4576 4.15385 17C4.15385 19.5261 4.9445 21.8675 6.29184 23.7902L5.23077 27.7692L9.27993 26.7569C11.1894 28.0746 13.5046 28.8462 16 28.8462Z" fill="#BFC8D0"/>
                    <path d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z" fill="url(#paint0_linear_87_7264)"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z" fill="white"/>
                    <path d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z" fill="white"/>
                    <defs>
                    <linearGradient id="paint0_linear_87_7264" x1="26.5" y1="7" x2="4" y2="28" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#5BD066"/>
                    <stop offset="1" stop-color="#27B43E"/>
                    </linearGradient>
                    </defs>
                </svg>
            </a>

    </div>
    <script>
        function showChat() {
            const chatElement = document.querySelector("#soporte")
            const buttonElement = document.querySelector("#buttonSoporte")
            chatElement.classList.remove('hidden')
            chatElement.classList.add('block')
            buttonElement.classList.remove('block')
            buttonElement.classList.add('hidden')
        }

        function hideChat() {
            const chatElement = document.querySelector("#soporte");
            const buttonElement = document.querySelector("#buttonSoporte");
            chatElement.classList.remove('block');
            chatElement.classList.add('hidden');
            buttonElement.classList.remove('hidden');
            buttonElement.classList.add('block');
        }

        window.addEventListener('downScroll', () => {
            var chat = document.getElementById('chatWithSeller');
            chat.scrollTop = chat.scrollHeight;
        })

        document.addEventListener('DOMContentLoaded', () => {
            var chat = document.getElementById('chatWithSeller');
            chat.scrollTop = chat.scrollHeight;
            chat.addEventListener("scroll", function() {
                if (chat.scrollTop === 0) {
                    // @this.totalMensajes = @this.totalMensajes + 2;
                }
            });
        })
    </script>
</div>
