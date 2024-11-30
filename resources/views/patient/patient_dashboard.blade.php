<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <title>Patient Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen bg-gray-100">

  <!-- Navigation Bar -->
  @include('patient.layouts.navigation')

  <!-- Main Content -->
  <main class="flex-grow">
    @yield('content')
  </main>

  <!-- Footer -->
  @include('footer')
</body>
</html>
