<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui vel laudantium atque neque suscipit, corporis esse
        ut. Quia, consectetur asperiores corrupti incidunt obcaecati sapiente. Maxime animi perferendis quidem
        temporibus necessitatibus.</p>
    @foreach ($data as $k => $v)
        <p>{{ $k . ' : ' . $v }}</p>
    @endforeach
</body>

</html>
