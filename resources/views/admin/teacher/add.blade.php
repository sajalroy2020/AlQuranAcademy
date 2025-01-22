@extends('layouts.app')

@push('title')
{{$pageTitle}}
@endpush

@section('content')
<!-- Page content area start -->
<div class="container-fluid py-4">
    <div class="p-30">
        <div>
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-3">
                <h5>{{$pageTitle}}</h5>
                <a href="{{route('admin.teacher.all')}}" class="btn btn-primary mb-0"><i class="fa fa-plus"></i> {{ __('Back') }}</a>
            </div>
            <div class="bg-white rounded p-3">
                <!-- class add form -->
                <form class="ajax-request reset" action="{{route('admin.teacher.store')}}" method="POST" data-handler="commonResponse">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" name="name" placeholder="{{ __('Full Name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2 mt-md-0 pt-md-0">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                      <input type="email" class="form-control" name="email" placeholder="{{__('Email') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group my-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label for="BatchName" class="form-label">{{ __('Gender Select') }} <span class="text-danger">*</span></label>
                                      <select class="form-control" id="BatchName" name="gender">
                                        <option value="">{{__("Select Gender")}}</option>
                                        <option value="{{GENDER_MALE}}">{{ __('Male') }}</option>
                                        <option value="{{GENDER_FEMALE}}">{{ __('Fimale') }}</option>
                                        <option value="{{GENDER_OTHERS}}">{{ __('Other') }}</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Father Name') }} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" name="father_name" placeholder="{{__('Father Name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Date Of Birth') }} <span class="text-danger">*</span></label>
                                      <input type="date" class="form-control" name="dob" placeholder="{{__('Date Of Birth') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group my-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label for="BatchName" class="form-label">{{ __('Marital Status') }} <span class="text-danger">*</span></label>
                                      <select class="form-control" id="BatchName" name="marital_status">
                                        <option value="">{{ __('Select Marital Status') }}</option>
                                        <option value="{{MARRIED}}">{{ __('Married') }}</option>
                                        <option value="{{UNMARRIED}}">{{ __('Unmarried') }}</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control" name="phone" placeholder="{{__('Enter phone')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Guardian phone: ')}} <span class="text-lighter">({{ __('Husband, Brother, Father, Mother')}})</span></label>
                                      <input type="number" class="form-control" name="guardian_phone" placeholder="{{ __('Enter guardian phone') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 pt-3">
                                <label for="BatchName" class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                                <select class="form-select form-control multiple-select-clear-field" data-placeholder="Select Teacher Course" multiple name="course_id[]">
                                    @foreach ($courseList as $data)
                                        <option value="{{$data->id}}">{{$data->subject_name}}</option>
                                    @endforeach
                                </select>
                                <div class="course_id"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Present Address: ')}} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" name="present_address" placeholder="{{ __('Present Address') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                      <label class="form-label">{{ __('Permanent Address: ')}} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" name="permanent_address" placeholder="{{ __('Permanent Address') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label">{{ __('শিক্ষাগত যোগ্যতা: ')}} <span class="text-lighter text-xxs">({{ __('কোন প্রতিষ্ঠান থেকে কত সালে এবং কোন বিভাগ/জামাত থেকে ফারেগ হয়েছেন তা বিস্তারিত লিখুন')}})</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="edu_qualification" placeholder="{{ __('শিক্ষাগত যোগ্যতা') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label">{{ __('ট্রেনিং যোগ্যতা: ')}} <span class="text-lighter text-xxs">({{ __('স্বীকৃত প্রাপ্ত কোন বোর্ড থেকে ট্রেনিং করেছেন কিনা? বোর্ডের নাম সহ পাশের সন উল্লেখ করুন।')}})</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="training_qualification" placeholder="{{ __('ট্রেনিং যোগ্যতা') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label">{{ __('অন্যান্য অতিরিক্ত যোগ্যতা: ')}} <span class="text-lighter text-xxs">({{ __('যেমন: কম্পিউটার অপারেটিং, বেসিক ইংলিশ, ইন্টারনেট ব্রাউজিং ইত্যাদি')}})</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="other_occupation" placeholder="{{ __('অন্যান্য অতিরিক্ত যোগ্যতা') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label">{{ __('পেশাগত তথ্য: ')}} <span class="text-lighter text-xxs">({{ __('পূর্বের এবং বর্তমানের পেশার বিস্তারিত বিবরণ লিখুন')}})</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="occupation_details" placeholder="{{ __('পেশাগত তথ্য') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="primary-form-group mt-2 pt-2">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label">{{ __('ক্লাস করানোর জন্য আপনার কোন ডিভাইসটি রয়েছে ')}} <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-4 align-items-center">
                                            <label>
                                                <input type="radio" class="form-check-input border" id="radio1" name="class_device" value="laptop">
                                                ল্যাপটপ
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input border me-1" id="radio2" name="class_device" value="desktop">
                                                ডেস্কটপ
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input border me-1" id="radio3" name="class_device" value="not">
                                                কোনটিই নেই
                                            </label>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h6>অনলাইন ক্লাসের পরিবেশের জন্য নিন্মোক্ত বিষয়গুলো কি আপনি বজায় রাখতে পারবেন? (গুরুত্বসহকারে পড়ে টিক দিন)</h6>
                        <div class="d-flex gap-3 pt-2">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">কোলাহলমুক্ত ক্লাসের পরিবেশ হওয়া। যেমন, ক্লাসের সময় ছোট বাচ্চাদের উপস্থিতি না থাকা, পারিবারিক ব্যাস্ততা শেষ করে ক্লাসে উপস্থিত হওয়া, ক্লাস চলাকালীন সময়ে মোবাইল সাইলেন্ট রাখা।</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">ক্যামেরায় ধারণকৃত অংশটি পরিপাটি হওয়া। যেমন, আপনার পেছনের অংশটিতে সুন্দর একটি পর্দা থাকা অথবা বই সমৃদ্ধ বুক সেলফ থাকা। কোন কাপড় ঝুলানো না থাকা বা পিছনে মানুষের চলাচল না থাকা ইত্যাদি।</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">নিরবিচ্ছিন্ন ব্রডব্যান্ড কানেকশন এবং মোবাইল ডাটার ব্যাবস্থা থাকা। যেমন, উন্নত মানের ব্রন্ডব্যান্ড কানেকশনের পাশাপাশি মাসিক প্যাকেজে মোবাইল ডাটা ব্যাবস্থা রাখা। কোন কারনে ইন্টারনেট কানেকশন বিচ্ছিন্ন হয়ে গেলে সাথে সাথে মোবাইল হট্স</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">কারণবশত বিদ্যুত বিভ্রাট হলে বা ল্যাপটপে চার্জ না থাকলে সাময়িক সময়ের জন্য মোবাইলে জুমের মাধ্যমে ক্লাস চালিয়ে নেওয়া। কোন অবস্থাতেই ক্লাস মিস না দেওয়া। অনিবার্য কারণবশত যদি জুম অ্যাপেও কারিগরি সমস্যা দেখা দেয় তাহলে শুধু সেই ক্লাসটি হোয়াটসঅ্যাপের মাধ্যমে করিয়ে সাথে সাথে একাডেমির অফিসে অবগত করে দ্রুত সমস্যার সমাধান করিয়ে নেওয়া।</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">অবশ্যই সর্বদা জুমের মাধ্যমে ক্লাস পরিচালনা করা। যেহেতু আমরা জুমের ফ্রি ভার্সন ব্যাবহার করে থাকি, তাই ৪০ মিনিট পরে ক্লাস অটোমেটিক বন্ধ হয়ে যায়। সেকারণে সাথে সাথেই পুনরায় ক্লাস চালু করে নির্ধারিত এক ঘন্টা পূর্ণ করা।</p>
                        </div>

                        <h6 class="pt-4">আমাদের অতি গুরুত্বপূর্ণ ক্লাসের সময় গুলোতে আপনি সময় দিতে পারবেন তো?</h6>

                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">শনিবার থেকে বৃহস্পতিবার ফজরের পর সকাল ৫ টা থেকে সকাল ৮ টা পর্যন্ত সময় দিতে হবে। (বাধ্যতামূলক)</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">শনিবার এবং রবিবার মাগরিবের পর থেকে রাত ১২ টা পর্যন্ত নূন্যতম তিনটি ক্লাসের জন্য তিন ঘন্টা সময় দিতে হবে। (বাধ্যতামূলক)</p>
                        </div>
                        <div class="d-flex gap-3">
                            <h6 class="text-danger">*</h6>
                            <p class="text-xs">এছাড়াও প্রয়োজনে সোমবার থেকে বৃহস্পতিবার মাগরিব থেকে রাত ১২ টা পর্যন্ত একটি বা দুটি ক্লাসের সময় দিতে হতে পারে।</p>
                        </div>

                        <p class="text-dark pt-3 pb-0"><strong>বিঃদ্রঃ-</strong> উপরোল্লিখিত সময় ছাড়া সারাদিনের অন্যান্ন সময়েও একাডেমির ক্লাস হয়ে থাকে। আপনারা চাইলে আমরা সেই সময়গুলোতে স্টুডেন্ট ভর্তি হলে আপনাদের ক্লাস দিতে পারবো ইনশাআল্লাহ।</p>
                        <p class="text-dark">আর একাডেমির ক্লাসের সময় সূচী নিয়ে যদি কোন প্রশ্ন থাকে তাহলে সরাসরি আমাদের হোয়াটসঅ্যাপে জিজ্ঞাসা করে বিষয়টি পরিস্কার হয়ে বুঝে নিবেন ইনশাআল্লাহ।</p>
                        <p class="text-dark"><strong>ছুটি সংক্রান্ত নীতিমালা - </strong> 
                            <span>একাডেমি কতৃক আপনাকে দেওয়া নির্ধারিত ক্লাসগুলো আমানতের সাতে যথাযথভাবে আদায় করতে হবে। যুক্তিসংগত কারনে যদি ছুটির প্রয়োজন হয় তাহলে <strong> প্রথমে অবশ্যই একাডেমিকে অবগত করে </strong> সম্মতি নিয়ে তারপর গার্ডিয়ানের সাথে কথা বলে ছুটি মঞ্জর করে নিতে হবে এবং পরবর্তীতে উক্ত ক্লাস সমূহ গার্ডিয়ানের সাথে আলোচনা করে উভয়ের সুবিধাজনক সময়ে আদায় করে দিতে হবে।</span><br>
                            <span class="mt-3 d-block">আর যদি কখনো গার্ডিয়ান বা স্টুডেন্ট ছুটি নেয় এবং উক্ত ক্লাসটি যদি পরবর্তী কোন সময়ে করে নিতে চায় তাহলে ক্লাসটি করিয়ে দেওয়ার জন্য সর্বচ্চ চেষ্টা করতে হবে।</span>
                        </p>
                    </div>

                    <div class="col-12">
                        <div class="primary-form-group mt-2 pt-2">
                            <div class="primary-form-group-wrap">
                                <label class="form-label f6">{{ __('আপনি কি উপরোল্লিখিত নীতিমাল যথাযথভাবে মেনে চলতে পারবেন ? ')}} <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 align-items-center">
                                    <label>
                                        <input type="radio" class="form-check-input border" id="radio1" name="is_all_agree" value="yes">
                                        হ্যাঁ
                                    </label>
                                    <label>
                                        <input type="radio" class="form-check-input border" id="radio2" name="is_all_agree" value="no">
                                        না
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('শিক্ষাগত যোগ্যতার সনদ সমূহ') }} <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" name="certificate_file" placeholder="{{__('শিক্ষাগত যোগ্যতার সনদ সমূহ')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('ভোটার আইডি বা জন্ম নিবন্ধনের কপি') }} <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" name="nid_file" placeholder="{{__('ভোটার আইডি বা জন্ম নিবন্ধনের কপি')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                  <input type="password" class="form-control" name="password" placeholder="********">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-25">{{ __('Submit Now') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Page content area end -->

<input type="hidden" id="teacher-list-route" value="{{ route('admin.teacher.all') }}">
<input type="hidden" id="get-state-route" value="{{ route('admin.teacher.get-state') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/js/teacher.js') }}"></script>
@endpush
