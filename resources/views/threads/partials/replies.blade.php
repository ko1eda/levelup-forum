
  @foreach($replies as $reply)

    <div class="card my-4 border border-info">

      <div class="card-header d-flex justify-content-between">
        Gerald
        <span class="text-right">
            {{ $reply->created_at->diffForHumans() }}
        </span>
      </div>

      <div class="card-body ">
        <p class="card-text">
          {{ $reply->body }}
        </p>
      
      </div>

    </div>

  @endforeach
