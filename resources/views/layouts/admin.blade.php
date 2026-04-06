<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Antigua Transfers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<div class="flex flex-col md:flex-row bg-[#0f1115]">
    @include('admin.partials.sidebar') <div class="main-content flex-1 bg-black   pb-24 md:pb-5">
        @yield('content') </div>
</div>
