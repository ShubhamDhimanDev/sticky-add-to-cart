<a href="{{ route('test',['token' => request()->token]) }}">go to test</a>


<form action="{{ route('test', ['token' => request()->token]) }}" method="POST">
    @csrf
    <input type="color" name="color" id="">
    <input type="text" name="name">

    <button type="submit">Submit</button>
</form>
