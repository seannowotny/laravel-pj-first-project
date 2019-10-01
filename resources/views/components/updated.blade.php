<p class="text-muted">
    {{ empty(trim($slot)) ? __('Added') : $slot }} {{ $date->diffForHumans() }}
  @if(isset($name))
      @if(isset($userID))
            {{ __('by') }} <a href="{{ route('users.show', ['user' => $userID]) }}">{{ $name }}</a>
      @else
            {{ __('by') }} {{ $name }}
      @endif
  @endif
</p>
