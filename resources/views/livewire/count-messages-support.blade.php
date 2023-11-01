<div>
    <div class="flex">
        <a class="cart-icon nav-link text-primary hover:text-primary " href="{{ route('seller.soporte') }}">
            <div  >
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" fill="white"
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                </svg>
            </div>
        </a>

        <div class="inline-flex items-center justify-center w-6 text-xs font-bold text-white bg-primary border-2 border-white rounded-full mb-2 dark:border-gray-900 px-2 ">
            {{ $total }}
        </div>
    </div>

   
</div>