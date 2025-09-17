 <!-- Footer Start -->
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-dark fw-semibold">Unique</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="fw-semibold text-primary text-decoration-underline">Unique</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>
        <!-- Footer End -->
        <div class="offcanvas offcanvas-end sidebar set-theme-colors" tabindex="-1" id="header-sidebar" aria-labelledby="header-sidebar">
            <div class="offcanvas-header border-bottom border-block-end-dashed">
                <h5 class="offcanvas-title" id="offcanvasRightLabel1">Notifications
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body rounded-0 p-0">
                <ul class="nav nav-tabs tab-style-1 d-flex border-bottom" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#chat" aria-current="page" href="#chat" aria-selected="false" role="tab" tabindex="-1"><i class="fe fe-mail me-1"></i>Chat</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#notifications" href="#notifications" aria-selected="false" role="tab" tabindex="-1"><i class="fe fe-activity me-1"></i>Activity</a>
                    </li>
                    <li class="nav-item mb-0" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#friends" href="#friends" aria-selected="true" role="tab"><i class="fe fe-edit-3 me-1"></i>Todo</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active border-start-0 border-end-0 rounded-0 p-0" id="chat" role="tabpanel">
                        <div class="input-group p-4">
                            <input type="text" placeholder="Search..." class="form-control search">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="px-4 text-dark fw-semibold">Today</div>
                        <ul class="mb-0 list-group list-group-flush">
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar online avatar-rounded flex-shrink-0 !w-20">
                                        <img src="{{asset('admin/images/users/male/1.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Maryam Naz</h6>
                                        <small class="text-muted fs-12">Thanks, maryam! talk later</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end mt-1">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">08:20 PM</h6>
                                    <span class="p-1 fs-7 lh-1 bg-success fw-semibold text-fixed-white rounded-circle">24</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/2.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Sahar Darya</h6>
                                        <small class="text-muted fs-12">No rush meet! lets go</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">08:00 PM</h6>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar online avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/3.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Maryam Naz</h6>
                                        <small class="text-muted fs-12">Okay. I'll tell him about it!</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">07:40 PM</h6>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/4.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Yolduz Rafi</h6>
                                        <small class="text-muted fs-12">I will text you later.</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">07:20 PM</h6>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar online avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/5.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Nargis Hawa</h6>
                                        <small class="text-muted fs-12">Yesterday we make fun a lot..</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end mt-1">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">07:00 PM</h6>
                                    <span class="p-1 fs-7 lh-1 bg-success fw-semibold text-fixed-white rounded-circle">10</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/6.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Khadija Mehr</h6>
                                        <small class="text-muted fs-12">Hey! buddy what's up...</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">06:10 PM</h6>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar online avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/7.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Khadija Mehr</h6>
                                        <small class="text-muted fs-12">Yeah! I knew..!</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end mt-1">
                                    <h6 class="text-dark fw-semibold fs-11 mb-0">05:20 PM</h6>
                                    <span class="p-1 fs-7 lh-1 bg-success fw-semibold text-fixed-white rounded-circle">06</span>
                                </div>
                            </li>
                        </ul>
                        <div class="px-4 text-dark fw-semibold">Yesterday</div>
        				<ul class="mb-0 list-group list-group-flush">
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/10.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Rishab</h6>
                                        <small class="text-muted fs-12">I have to go...!</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <span class="text-dark fw-semibold fs-11">11:20 AM</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/1.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Scarlet</h6>
                                        <small class="text-muted fs-12">Hey! there I' am available....</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <span class="text-dark fw-semibold fs-11">10:00 AM</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/9.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Willson</h6>
                                        <small class="text-muted fs-12">Today I completed my work.!</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <span class="text-dark fw-semibold fs-11">09:50 AM</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex border-0">
                                <div class="d-flex">
                                    <span class="avatar avatar-rounded flex-shrink-0">
                                        <img src="{{asset('admin/images/users/male/11.jpg')}}" alt="img">
                                    </span>
                                    <a href="chat.html" class="ms-2 p-0">
                                        <h6 class="mt-1 mb-0 fw-semibold fs-13">Yolduz Rafi</h6>
                                        <small class="text-muted fs-12">Okay...I will be wait for you</small>
                                    </a>
                                </div>
                                <div class="ms-auto text-end">
                                    <span class="text-dark fw-semibold fs-11">09:20 AM</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane border-start-0 border-end-0 rounded-0 p-0" id="notifications" role="tabpanel">
                        <div class="list-group list-group-flush ">
                            <div class="text-dark fw-semibold bg-light px-4 py-2">Today</div>
                            <ul class="list-group list-group-flush mb-2 mt-4">
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-file-plus p-3 fs-6 bg-primary-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            New websites is created
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>36s
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-briefcase icons-padding-space fs-6 bg-danger-transparent rounded-circle"></i>
                                    </div>
                                    <div class=" w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Prepare for the next project
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock ed me-1"></i>2 mins
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-clock p-3 fs-6 bg-info-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Decide the live discussion time
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>10 mins
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-users p-3 fs-6 bg-success-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Team review meeting at yesterday at 3:00 pm
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>1 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-book-open p-3 fs-6 bg-pink-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Prepare for presentation
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>3 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-check-circle p-3 fs-6 bg-purple-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Willson jake was created a task
                                        </h6>
                                        <span class="text-muted">
                                            <i class="mdi mdi-clock me-1"></i>5 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-mail p-3 fs-6 bg-orange-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class=" w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Barina kilton commented on your designs
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>10 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div>
                                        <i class="fe fe-zap p-3 fs-6 bg-secondary-transparent rounded-circle icons-padding-space"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold fs-13">
                                            Juline klept shared a file-attachments
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock text-muted me-1"></i>12 hr
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <div class="text-dark fw-semibold bg-light px-4 py-2">Last 7 Days</div>
        							<ul class="list-group list-group-flush mt-4">
        								<li class="list-group-item d-flex align-items-center border-0">
        									<div>
        										<i class="fe fe-calendar p-3 fs-6 bg-primary-transparent rounded-circle icons-padding-space"></i>
        									</div>
        									<div class="w-100 ms-3">
        										<h6 class="mb-0 fw-semibold fs-13">
        											Robert veltz was completed project
        										</h6>
        										<span class="text-muted fs-12">
        											<i class="mdi mdi-clock me-1"></i>14 May
        										</span>
        									</div>
        								</li>
        								<li class="list-group-item d-flex align-items-center border-0">
        									<div>
        										<i class="fe fe-user-check p-3 fs-6 bg-danger-transparent rounded-circle icons-padding-space"></i>
        									</div>
        									<div class="w-100 ms-3">
        										<h6 class="mb-0 fw-semibold fs-13">
        											Completed for the next meeting
        										</h6>
        										<span class="text-muted fs-12">
        											<i class="mdi mdi-clock me-1"></i>16 May
        										</span>
        									</div>
        								</li>
        								<li class="list-group-item d-flex align-items-center border-0">
        									<div>
        										<i class="fe fe-clock p-3 fs-6 bg-info-transparent rounded-circle icons-padding-space"></i>
        									</div>
        									<div class="w-100 ms-3">
        										<h6 class="mb-0 fw-semibold fs-13">
        											Decide the live discussion time
        										</h6>
        										<div class="d-flex justify-content-between align-items-center">
        											<span class="text-muted fs-12">
        												<i class="mdi mdi-clock me-1"></i>20 May
        											</span>
        										</div>
        									</div>
        								</li>
        								<li class="list-group-item d-flex align-items-center border-0">
        									<div>
        										<i class="fe fe-users p-3 fs-6 bg-success-transparent rounded-circle icons-padding-space"></i>
        									</div>
        									<div class=" w-100 ms-3">
        										<h6 class="mb-0 fw-semibold fs-13">
        											Team review meeting at yesterday at 3:00 pm
        										</h6>
        										<span class="text-muted fs-12">
        											<i class="mdi mdi-clock me-1"></i>22 May
        										</span>
        									</div>
        								</li>
        							</ul>
                        </div>
                    </div>
                    <div class="tab-pane border-start-0 border-end-0 rounded-0 p-0 show" id="friends" role="tabpanel">
                        <div class="list-group list-group-flush ">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox1"
                                            value="option1" checked="">
                                        <span class="custom-control-label fw-semibold fw-semibold">Do Even More..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox2"
                                            value="option2" checked="">
                                        <span class="custom-control-label fw-semibold">Find an idea.</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox3"
                                            value="option3" checked="">
                                        <span class="custom-control-label fw-semibold">Hangout with friends</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox4"
                                            value="option4">
                                        <span class="custom-control-label fw-semibold">Do Something else</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox5"
                                            value="option5">
                                        <span class="custom-control-label fw-semibold">Eat healthy, Eat Fresh..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox6"
                                            value="option6" checked="">
                                        <span class="custom-control-label fw-semibold">Finsh something more..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox7"
                                            value="option7" checked="">
                                        <span class="custom-control-label fw-semibold">Do something more</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox8"
                                            value="option8">
                                        <span class="custom-control-label fw-semibold">Updated more files</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox9"
                                            value="option9">
                                        <span class="custom-control-label fw-semibold">System updated</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item border-bottom d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="form-check-input me-2" name="example-checkbox10"
                                            value="option10">
                                        <span class="custom-control-label fw-semibold">Settings Changings...</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <div class="text-center p-4">
                                <a href="javascript:void(0);" class="btn btn-primary w-100">Upgrade more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <script src="{{asset('admin/libs/@popperjs/core/umd/popper.min.js')}}"></script>
    <script src="{{asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/js/defaultmenu.min.js')}}"></script>
    <script src="{{asset('admin/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('admin/js/sticky.js')}}"></script>
    <script src="{{asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('admin/js/simplebar.js')}}"></script>
    <script src="{{asset('admin/libs/@simonwep/pickr/pickr.es5.min.js')}}"></script>
    <script src="{{asset('admin/libs/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('admin/js/page-header-scroll.js')}}"></script>
    <script src="{{asset('admin/libs/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('admin/js/index-6.js')}}"></script>
    <script src="{{asset('admin/js/custom-switcher.min.js')}}"></script>
    <script src="{{asset('admin/js/custom.js')}}"></script>

</body>

</html>
