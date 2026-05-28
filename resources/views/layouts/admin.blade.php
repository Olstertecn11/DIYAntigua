<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Antigua Transfers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html {
            scrollbar-color: rgba(252, 202, 0, .72) rgba(255, 255, 255, .06);
            scrollbar-width: thin;
        }

        *::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        *::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, .05);
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #FCCA00, #555);
            border: 2px solid rgba(0, 0, 0, .35);
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: #FCCA00;
        }
    </style>
</head>
<div class="flex flex-col md:flex-row bg-[#0f1115]">
    @include('admin.partials.sidebar') <div class="main-content flex-1 bg-black   pb-24 md:pb-5">
        @yield('content') </div>
</div>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
