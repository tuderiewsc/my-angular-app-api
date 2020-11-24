<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Upload</title>
</head>
<body>

  @if (\Session::has('uploadSuccess'))
      <script>alert('{{ \Session::get('uploadSuccess') }}')</script>
  @endif

  <form action="{{ route('uploadImage') }}" method="POST" enctype="multipart/form-data">
    @csrf
  <input type="file" name="images[]" multiple>
    <label for="which_section">section:</label>
    <select id="which_section" name="section">
      <option value="article">Article</option>
      <option value="profile">Profile</option>
    </select>
  <input type="submit" value="send">
  </form>

</body>
</html>