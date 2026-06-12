<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Top Care Group Admin</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="bg-[#edf3ef]">
    <div id="admin-app" data-props='@json($adminProps)'></div>
</body>
</html>
