<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="container mx-auto p-8">
  <h1>Create Project</h1>

  <form action="/projects" method="post">
    @csrf
    <div class="p-6">
      <input type="text" name="title" value="{{ @old('title') }}" class="p-4 text-gray-300 border border-blue-300" placeholder="Title goes here">
    </div>
    <div class="p-6">
      <textarea name="description" id="description" cols="30" rows="10" class="p-4 text-gray-300 border border-blue-300" placeholder="Description goes here">{{ @old('description') }}</textarea>
    </div>

    <button class="bg-blue-600 p-4 text-white rounded-md" type="submit">Create Project</button>
  </form>

</div>
</body>
</html>
