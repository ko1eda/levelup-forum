<div class="lu-card tw-px-4">
  <div class="lu-card-header tw-px-4 tw-text-center ">
    <span class=""> 
      Trending Threads 
    </span>
  </div>{{-- end card header --}}

  @foreach($trendingThreads as $thread)
    <div class="tw-py-4 lg:tw-p-4 tw-text-sm tw-flex tw-justify-between tw-items-center">
      <div class="tw-flex tw-flex-col tw-w-full">    

        <span class="tw-pr-4 md:tw-pr-0 lg:tw-pr-4 tw-mb-2">
          <a href="{{ $thread->uri }}">{{ $thread->title }}</a>
        </span>


        <div class="lu-level tw-px-0 tw-py-0 tw-text-xs ">

          <div class="lu-level-item tw-text-grey-darker tw-w-32 lg:tw-w-32 md:tw-justify-center tw-mr-0" title="Thread Owner">
            <i class="fas fa-user tw-mr-1 tw-align-middle "></i>
            <a href={{ route('profiles.show', $thread->username) }}>
              <span class="tw-text-green hover:tw-text-green-dark tw-font-semibold tw-align-middle">
                {{ $thread->username }}
              </span>
            </a>
          </div>{{-- end user name --}}
    
          <div class="lu-level-item tw-mr-0 " title="Thread channel">
            <i class="fas fa-folder-open tw-mr-1 tw-text-grey-darker tw-align-middle "></i>
            <a href="{{$thread->channelUri}}">
              <span class="tw-align-middle">
                / {{ $thread->channel }}
              </span>
            </a>
          </div>{{-- end replies count --}}
        </div>{{-- end level --}}

      </div>{{-- end text column with level --}}


      <span class="tw-text-center lg:tw-mr-2">
        <span class="">
          {{ $thread->view_count }}
        </span>

        <br>
        Points
      </span>{{-- end view count column --}}
      
    </div>{{-- end thread list --}}
    @endforeach

</div>{{-- end lu-card --}}