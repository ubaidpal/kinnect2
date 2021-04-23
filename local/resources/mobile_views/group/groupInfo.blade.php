<div class="content-gray-title mb10">
    <h4>Group Information</h4>
    @can('update', $group)
    <a title="Browse" class="btn fltR" href="{{ URL::to('group/edit/'.$group->id)}}">Edit</a>
    @endcan
</div>
<div class="details-list">
    <div class="detail-item">
        <div class="dtl-item">
            <span>Title &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$group->title}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Description &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$group->description}}</span>
        </div>
    </div>

    <div class="detail-item">
        <div class="dtl-item">
            <span>Category &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$cat->title}}</span>
        </div>
    </div>

    <div class="detail-item">
        <div class="dtl-item">
            <span>Owner Name</span>
        </div>
        <div class="dtl-value">
            <?php $groupOwner = Kinnect2::groupOwner( $group->creator_id ); ?>
            <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{$groupOwner->displayname}}</a>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Managers Name</span>
        </div>
        <div class="dtl-value">
            <?php $groupManagers = Kinnect2::groupManagers( $group->id ); ?>
            @if($groupManagers == 0)
                <span>No Manager</span>
            @else
                @foreach($groupManagers as $groupManager)
                    <a href="{{url(Kinnect2::profileAddress($groupManager))}}" style="display: list-item"> {{$groupManager->displayname}}</a>
                @endforeach
            @endif
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Total Views</span>
        </div>
        <div class="dtl-value">
            <span>{{$group->view_count}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Total Members</span>
        </div>
        <div class="dtl-value">
            <span>{{ Kinnect2::followers($group['id']) }}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Last Updated</span>
        </div>
        <div class="dtl-value">
            <span>{{$group->updated_at}}</span>
        </div>
    </div>
    <div class="content-gray-title mb10">
        <h4>About Group</h4>
    </div>
</div>