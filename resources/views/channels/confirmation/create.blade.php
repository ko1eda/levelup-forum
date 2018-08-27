@extends('layouts.app')


@section('content')
<div class="columns is-centered">
  <div class="column is-9-tablet is-6-desktop">
  <lu-card :data="{{ json_encode($data) }}" :approve-uri={{ json_encode(route('channels.confirm.store', '', false)) }} :decline-uri={{ json_encode(route('channels.confirm.destroy', '', false)) }}>

      <template slot="photo" slot-scope="{ data }">
        <img :src="data[0].profile.profile_photo_path" alt="User Avatar">
      </template>

      <template slot="username" slot-scope="{ data }">
        <span v-text="'@' + data[0].username" class=""></span>
      </template>

      <template  slot="card-title" slot-scope="{ data }">
        <span class="tw-text-base md:tw-text-2xl md:tw-font-light ">
          Requested a new channel!
        </span>
      </template>

      <template  slot="body" slot-scope="{ data }">
        <p class="tw-text-sm md:tw-text-base ">
          <span>Name:</span>
          <span v-text="data[1].name" class=""></span>
        </p>
        <p class="tw-text-sm md:tw-text-base ">
          <span>Tagline:</span>
          <span v-text="data[1].description" class="tw-"></span>
        </p>
      </template>


      <div class="field is-grouped is-grouped-centered" slot="buttons" slot-scope="{approve, decline}">
        <p class="control">
          <a class="button is-primary tw-w-32 md:tw-w-48 is-small" @click="approve" >
            Approve
          </a>
        </p>

        <p class="control">
          <a class="button is-danger tw-w-32 md:tw-w-48 is-small" @click="decline">
            Decline
          </a>
        </p>
      </div>

    </lu-card>
  </div>
</div>
@endsection