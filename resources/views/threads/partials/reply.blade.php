<div class="card my-4 border border-info">
  <div class="card-header d-flex justify-content-between">
    <a href="#">
     {{$reply->user->name}}
    </a>

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



<div class="twflex twflex-col twbg-white twbr-1 twshadow-md twrounded tww-full ">
    <div class="twbg-grey-lighter twpx-8 twpy-2 twflex twjustify-between">
      <a href="#">
        <strong>
          {{$reply->user->name}}
        </strong>
      </a> 
      <div>
        {{ $reply->created_at->diffForHumans() }}
      </div>
    </div>

    <div class="twpy-6 twpx-8 twleading-loose twtext-grey-darker"> 
      {{ $thread->body }}
    </div>  
    <div class="twpx-8">
  

    </div>

  </div>