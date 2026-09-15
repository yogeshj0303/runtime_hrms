@extends('layouts.master')

@section('title')
    Org Wall
@endsection

@section('css')
<style>
/* =========================
TOP HEADER
========================= */
.setup-top{
    margin-bottom:24px;
}

.breadcrumb{
    font-size:13px;
    color:#667085;
    font-weight:500;
    margin-bottom:12px;
    display:block;
}

.title-row{
    display:flex;
    gap:14px;
    align-items:flex-start;
}

.title-row i{
    font-size:30px;
    color:#133C5A;
}

.title-row h2{
    font-size:22px;
    font-weight:700;
    color:#133C5A;
    margin-bottom:4px;
}

.title-row p{
    font-size:14px;
    color:#667085;
    font-weight:400;
}

/* =========================
LAYOUT
========================= */
.setup-wrap{
    display:flex;
    gap:24px;
    align-items:flex-start;
}

/* =========================
SIDEBAR
========================= */
.sidebar{
    width:290px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    overflow:hidden;
    position:sticky;
    top:15px;
    box-shadow:0 4px 20px rgba(0,0,0,.04);
}

.menu{
    list-style:none;
    margin:0;
    padding:0;
}

.menu li{
    padding:14px 18px;
    display:flex;
    align-items:center;
    gap:12px;
    color:#475467;
    font-size:14px;
    font-weight:500;
    cursor:pointer;
    border-bottom:1px solid #f3f4f6;
    transition:.3s;
}

.menu li:last-child{
    border-bottom:none;
}

.menu li i{
    font-size:18px;
    color:#667085;
    transition:.3s;
}

.menu li:hover{
    background:#f8fafc;
    color:#133C5A;
}

.menu li:hover i{
    color:#133C5A;
}

.menu li.active{
    background:#133C5A;
    color:#fff;
}

.menu li.active i{
    color:#fff;
}

/* =========================
TAB CONTENT
========================= */
.cards{
    flex:1;
}

.tab-content{
    display:none;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    animation:fadeIn .5s ease forwards;
}

.tab-content.active{
    display:grid;
}

@keyframes fadeIn{
    from{ opacity:0; transform:translateY(10px); }
    to{ opacity:1; transform:translateY(0); }
}

/* =========================
CARD DESIGN
========================= */
.cardx{
    background:#fff;
    border:1px solid #e4e7ec;
    border-radius:18px;
    min-height:230px;
    padding:22px;
    display:flex;
    flex-direction:column;
    text-align:center;
    transition:all .35s ease;
    position:relative;
    overflow:hidden;
}

.cardx::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
    background:linear-gradient(90deg,#133C5A,#2f80ed);
}

.cardx:hover{
    transform:translateY(-6px);
    border-color:#133C5A;
    box-shadow:0 18px 40px rgba(0,0,0,.08);
}

.card-content{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.cardx .icon{
    width:72px;
    height:72px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:rgba(19,60,90,.08);
    color:#133C5A;
    font-size:34px;
    margin-bottom:16px;
    transition:.3s;
}

.cardx .icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.cardx:hover .icon{
    transform:scale(1.08);
}

.cardx h4{
    font-size:17px;
    font-weight:600;
    color:#133C5A;
    margin-bottom:8px;
    line-height:1.4;
}

.cardx p{
    font-size:13px;
    color:#667085;
    line-height:1.7;
    font-weight:400;
}

.btn-open{
    margin-top:18px;
    padding:11px 14px;
    border-radius:10px;
    text-decoration:none;
    text-align:center;
    color:#fff;
    font-size:13px;
    font-weight:600;
    letter-spacing:.4px;
    background:linear-gradient(135deg,#133C5A,#1d567f);
    transition:.3s ease;
    border: none;
    cursor: pointer;
    width: 100%;
}

.btn-open:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(19,60,90,.25);
    color: #fff;
}

.btn-posted{
    background: #eef2f7;
    color: #98a2b3;
    pointer-events: none;
    box-shadow: none;
}

/* =========================
RESPONSIVE
========================= */
@media(max-width:1400px){
    .tab-content{
        grid-template-columns:repeat(3,1fr);
    }
}
@media(max-width:1200px){
    .tab-content{
        grid-template-columns:repeat(2,1fr);
    }
}
@media(max-width:992px){
    .setup-wrap{
        flex-direction:column;
    }
    .sidebar{
        width:100%;
        position:relative;
        top:0;
    }
}
@media(max-width:576px){
    .tab-content{
        grid-template-columns:1fr;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- HEADER -->
    <div class="setup-top">
        <div class="breadcrumb">Wall / Management</div>
        <div class="title-row">
            <i class="ri-message-3-line"></i>
            <div class="d-flex align-items-center justify-content-between w-100">
                <div>
                    <h2>Org Wall</h2>
                    <p>Manage and post upcoming employee events and announcements.</p>
                </div>
                <button class="btn btn-primary" onclick="openPostModal('general', null, '', '', 'Write a general announcement here...')">
                    <i class="ri-add-line align-bottom me-1"></i> Create General Post
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="setup-wrap">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <ul class="menu">
                <li class="active" data-tab="birthdays">
                    <i class="ri-cake-2-line"></i> Upcoming Birthdays
                    <span class="badge bg-soft-primary text-primary ms-auto rounded-pill">{{ count($birthdays) }}</span>
                </li>
                <li data-tab="anniversaries">
                    <i class="ri-award-line"></i> Work Anniversaries
                    <span class="badge bg-soft-primary text-primary ms-auto rounded-pill">{{ count($anniversaries) }}</span>
                </li>
                <li data-tab="joinees">
                    <i class="ri-user-add-line"></i> New Joinees
                    <span class="badge bg-soft-primary text-primary ms-auto rounded-pill">{{ count($joinees) }}</span>
                </li>
                <li data-tab="general">
                    <i class="ri-megaphone-line"></i> General Posts
                    <span class="badge bg-soft-primary text-primary ms-auto rounded-pill">{{ count($generalPosts) }}</span>
                </li>
            </ul>
        </div>

        <!-- CARDS -->
        <div class="cards">

            <!-- BIRTHDAYS TAB -->
            <div class="tab-content active" id="birthdays">
                @forelse($birthdays as $emp)
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon" style="background:transparent; padding:0;">
                            <img src="{{ $emp->user->avatar ? asset($emp->user->avatar) : asset('assets/images/users/user-dummy-img.jpg') }}" alt="">
                        </div>
                        <h4 class="text-truncate w-100 px-2" title="{{ $emp->first_name }} {{ $emp->last_name }}">{{ $emp->first_name }} {{ $emp->last_name }}</h4>
                        <div class="text-muted mb-2" style="font-size:12px; line-height: 1.4;">
                            <span class="d-block fw-medium text-dark">{{ $emp->employee_code }}</span>
                            <span class="d-block text-truncate px-2">{{ $emp->designation ?? 'Employee' }}</span>
                        </div>
                        <span class="badge bg-soft-primary text-primary fs-12 px-2 py-1 mb-2"><i class="ri-calendar-event-line align-bottom me-1"></i> {{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}</span>
                    </div>
                    @if($emp->is_posted)
                        <form action="{{ route('wall.destroy_event', $emp->post_id) }}" method="POST" class="w-100" style="margin-top:18px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-open" style="background:#fff; border: 1px solid #dc3545; color: #dc3545; box-shadow: none;">Undo Post</button>
                        </form>
                    @else
                        <button class="btn-open" onclick="openPostModal('birthday', {{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', '{{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}', 'Happy Birthday, {{ addslashes($emp->first_name) }}! Wishing you a fantastic day ahead.')">Post</button>
                    @endif
                </div>
                @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="text-center p-5 bg-white rounded-3 border" style="border-color: #e4e7ec;">
                        <i class="ri-cake-2-line text-muted mb-3 d-block" style="font-size:40px;"></i>
                        <h5 class="text-muted">No upcoming birthdays</h5>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- ANNIVERSARIES TAB -->
            <div class="tab-content" id="anniversaries">
                @forelse($anniversaries as $emp)
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon" style="background:transparent; padding:0;">
                            <img src="{{ $emp->user->avatar ? asset($emp->user->avatar) : asset('assets/images/users/user-dummy-img.jpg') }}" alt="">
                        </div>
                        <h4 class="text-truncate w-100 px-2" title="{{ $emp->first_name }} {{ $emp->last_name }}">{{ $emp->first_name }} {{ $emp->last_name }}</h4>
                        <div class="text-muted mb-2" style="font-size:12px; line-height: 1.4;">
                            <span class="d-block fw-medium text-dark">{{ $emp->employee_code }}</span>
                            <span class="d-block text-truncate px-2">{{ $emp->designation ?? 'Employee' }}</span>
                        </div>
                        <span class="badge bg-soft-primary text-primary fs-12 px-2 py-1 mb-2"><i class="ri-calendar-event-line align-bottom me-1"></i> {{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}</span>
                    </div>
                    @if($emp->is_posted)
                        <form action="{{ route('wall.destroy_event', $emp->post_id) }}" method="POST" class="w-100" style="margin-top:18px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-open" style="background:#fff; border: 1px solid #dc3545; color: #dc3545; box-shadow: none;">Undo Post</button>
                        </form>
                    @else
                        <button class="btn-open" onclick="openPostModal('anniversary', {{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', '{{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}', 'Happy Work Anniversary, {{ addslashes($emp->first_name) }}! Thank you for your dedication.')">Post</button>
                    @endif
                </div>
                @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="text-center p-5 bg-white rounded-3 border" style="border-color: #e4e7ec;">
                        <i class="ri-award-line text-muted mb-3 d-block" style="font-size:40px;"></i>
                        <h5 class="text-muted">No upcoming anniversaries</h5>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- JOINEES TAB -->
            <div class="tab-content" id="joinees">
                @forelse($joinees as $emp)
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon" style="background:transparent; padding:0;">
                            <img src="{{ $emp->user->avatar ? asset($emp->user->avatar) : asset('assets/images/users/user-dummy-img.jpg') }}" alt="">
                        </div>
                        <h4 class="text-truncate w-100 px-2" title="{{ $emp->first_name }} {{ $emp->last_name }}">{{ $emp->first_name }} {{ $emp->last_name }}</h4>
                        <div class="text-muted mb-2" style="font-size:12px; line-height: 1.4;">
                            <span class="d-block fw-medium text-dark">{{ $emp->employee_code }}</span>
                            <span class="d-block text-truncate px-2">{{ $emp->designation ?? 'Employee' }}</span>
                        </div>
                        <span class="badge bg-soft-primary text-primary fs-12 px-2 py-1 mb-2"><i class="ri-calendar-event-line align-bottom me-1"></i> {{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}</span>
                    </div>
                    @if($emp->is_posted)
                        <form action="{{ route('wall.destroy_event', $emp->post_id) }}" method="POST" class="w-100" style="margin-top:18px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-open" style="background:#fff; border: 1px solid #dc3545; color: #dc3545; box-shadow: none;">Undo Post</button>
                        </form>
                    @else
                        <button class="btn-open" onclick="openPostModal('new_joinee', {{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', '{{ \Carbon\Carbon::parse($emp->event_date)->format('d M') }}', 'Welcome to the team, {{ addslashes($emp->first_name) }}! We are excited to have you on board.')">Post</button>
                    @endif
                </div>
                @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="text-center p-5 bg-white rounded-3 border" style="border-color: #e4e7ec;">
                        <i class="ri-user-add-line text-muted mb-3 d-block" style="font-size:40px;"></i>
                        <h5 class="text-muted">No recent joinees</h5>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- GENERAL POSTS TAB -->
            <div class="tab-content" id="general">
                @forelse($generalPosts as $post)
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon" style="background:transparent; padding:0;">
                            <img src="{{ $post->image_url ? $post->image_url : asset('assets/images/users/user-dummy-img.jpg') }}" alt="">
                        </div>
                        <h4 class="text-truncate w-100 px-2" title="{{ Str::limit($post->content, 50) }}">{{ Str::limit($post->content, 20) }}</h4>
                        <div class="text-muted mb-2" style="font-size:12px; line-height: 1.4;">
                            <span class="d-block fw-medium text-dark">General Announcement</span>
                            <span class="d-block px-2">
                                @if($post->valid_from || $post->valid_to)
                                    {{ $post->valid_from ? \Carbon\Carbon::parse($post->valid_from)->format('d M y') : 'Now' }} - {{ $post->valid_to ? \Carbon\Carbon::parse($post->valid_to)->format('d M y') : 'Forever' }}
                                @else
                                    Always Visible
                                @endif
                            </span>
                        </div>
                        <span class="badge bg-soft-primary text-primary fs-12 px-2 py-1 mb-2"><i class="ri-calendar-event-line align-bottom me-1"></i> {{ $post->created_at->format('d M Y') }}</span>
                    </div>
                    <form action="{{ route('wall.destroy_event', $post->id) }}" method="POST" class="w-100" style="margin-top:18px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-open" style="background:#fff; border: 1px solid #dc3545; color: #dc3545; box-shadow: none;">Undo Post</button>
                    </form>
                </div>
                @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="text-center p-5 bg-white rounded-3 border" style="border-color: #e4e7ec;">
                        <i class="ri-megaphone-line text-muted mb-3 d-block" style="font-size:40px;"></i>
                        <h5 class="text-muted">No general posts yet</h5>
                    </div>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<!-- Compose Post Modal -->
<div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('wall.store_event') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Create Wall Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="post_type" value="general">
                    <input type="hidden" name="target_employee_id" id="target_employee_id" value="">
                    
                    <div id="employeeInfoSection" class="mb-3 d-none p-3 bg-light rounded border">
                        <strong>Target Employee:</strong> <span id="modalEmployeeName"></span><br>
                        <strong>Event Date:</strong> <span id="modalEventDate"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Message Content</label>
                        <textarea name="content" id="post_content" class="form-control" rows="4" required></textarea>
                    </div>

                    <div id="validitySection" class="row mb-3 d-none">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Valid From (Optional)</label>
                            <input type="date" name="valid_from" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Valid To (Optional)</label>
                            <input type="date" name="valid_to" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text">Max size: 5MB. Leave blank for text-only posts.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Publish to Wall</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    // Tab Switching Logic
    const menuItems = document.querySelectorAll('.menu li[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');

    // Restore active tab from localStorage if it exists
    const activeWallTab = localStorage.getItem('activeWallTab');
    if (activeWallTab) {
        const savedTabItem = document.querySelector(`.menu li[data-tab="${activeWallTab}"]`);
        if (savedTabItem) {
            menuItems.forEach(i => i.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));

            savedTabItem.classList.add('active');
            document.getElementById(activeWallTab).classList.add('active');
        }
    }

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove active from all tabs
            menuItems.forEach(i => i.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));

            // Add active to clicked
            item.classList.add('active');
            const targetId = item.getAttribute('data-tab');
            document.getElementById(targetId).classList.add('active');

            // Save to localStorage
            localStorage.setItem('activeWallTab', targetId);
        });
    });

    // Modal Logic
    function openPostModal(type, employeeId, employeeName, eventDate, defaultMessage) {
        document.getElementById('post_type').value = type;
        document.getElementById('target_employee_id').value = employeeId || '';
        document.getElementById('post_content').value = defaultMessage || '';
        
        const empInfo = document.getElementById('employeeInfoSection');
        const validitySection = document.getElementById('validitySection');

        if (type !== 'general' && employeeId) {
            document.getElementById('modalEmployeeName').innerText = employeeName;
            document.getElementById('modalEventDate').innerText = eventDate;
            empInfo.classList.remove('d-none');
            validitySection.classList.add('d-none');
        } else {
            empInfo.classList.add('d-none');
            validitySection.classList.remove('d-none');
        }

        var postModal = new bootstrap.Modal(document.getElementById('postModal'));
        postModal.show();
    }
</script>
@endsection
