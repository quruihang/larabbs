<div class="reply-list">
    @foreach ($replies as $index => $reply)
        <div class=" media"  name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">
            <div class="avatar pull-left">
                <a href="{{ route('users.show', [$reply->user_id]) }}">
                    <img class="media-object img-thumbnail" alt="{{ $reply->user->name }}" src="{{ $reply->user->avatar }}"  style="width:48px;height:48px;"/>
                </a>
            </div>

            <div class="infos">
                <div class="media-heading">
                    <a href="{{ route('users.show', [$reply->user_id]) }}" title="{{ $reply->user->name }}">
                        {{ $reply->user->name }}
                    </a>
                    <span> •  </span>
                    <span class="meta" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>

                    {{-- 回复删除按钮 --}}
                    {{--只有当用户拥有删除回复权限时，才显示按钮--}}
                    @can('destroy', $reply)
                        <span class="meta float-right">
                            <form action="{{ route('replies.destroy', $reply->id) }}"
                                  onsubmit="return confirm('确定要删除此评论？');"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-default btn-xs pull-left text-secondary">
                                <i class="far fa-trash-alt"></i>
                              </button>
                            </form>
                        </span>
                    @endcan
                </div>
                <div class="reply-content">
                    {{--使用 {!! !!} Blade 表达式，意味着非转义打印数据--}}
                    {!! $reply->content !!}
                </div>
            </div>
        </div>
        <hr>
    @endforeach
</div>