<form action="/questions/{{ $model->id }}/favorites" id="favorite-question-{{ $model->id }}" method="POST" style="display:none;">
    @csrf
    @if ($model->is_favorited)
        @method('DELETE')
    @endif
</form>
