<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <title>Admin Dashboard</title>
  <!-- Add FontAwesome CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body class="flex flex-col min-h-screen bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto">
            @yield('content')
        </div>
    </div>


</body>
</html>
