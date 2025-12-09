<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Admin | Dashboard')</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- SEO Meta Tags -->
    @if(setting('meta_description'))
        <meta name="description" content="{{ setting('meta_description') }}">
    @endif
    @if(setting('meta_keywords'))
        <meta name="keywords" content="{{ setting('meta_keywords') }}">
    @endif

    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ setting('social_title') ?: setting('site_title') ?: 'Admin Dashboard' }}">
    @if(setting('social_description'))
        <meta property="og:description" content="{{ setting('social_description') }}">
    @elseif(setting('meta_description'))
        <meta property="og:description" content="{{ setting('meta_description') }}">
    @endif
    @if(setting('og_image_path'))
        <meta property="og:image" content="{{ asset(setting('og_image_path')) }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="og:type" content="website">

    @if(setting('favicon_path'))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('favicon_path')) }}" />
    @else
        <link rel="shortcut icon" type="image/png" href="{{ asset('admin/images/favicon.png') }}" />
    @endif
    <link href="{{ asset('admin/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet" />
    <link href="{{ asset('admin/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />

    @php
        // Derive a dynamic primary color from session/cookie/config
        $cookiePrimary = request()->cookie('primary');
        // Map template theme keys to hex colors (adjust as needed)
        $themeMap = [
            'color_1' => '#36C95F',
            'color_2' => '#4d7cff',
            'color_3' => '#ff6b6b',
            'color_4' => '#f59e0b',
            'color_5' => '#8b5cf6',
        ];
        $configured = config('app.primary_color');
        $primaryColor = $configured ?: ($themeMap[$cookiePrimary] ?? '#36C95F');
    @endphp
    <style>
        :root {
            --primary:
                {{ $primaryColor }}
            ;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .badge-primary {
            background-color: var(--primary);
        }

        .border-primary {
            border-color: var(--primary) !important;
        }

        .fill-primary {
            fill: var(--primary);
        }

        .stroke-primary {
            stroke: var(--primary);
        }

        /* Ensure readable text colors across cards and forms */
        body,
        .card,
        .card-body,
        .card-header,
        .form-label,
        .form-control,
        .dropdown-menu,
        .breadcrumb-item,
        .dz-message {
            color: #1f2937;
            /* slate-800 */
        }

        .card-header.bg-primary,
        .badge.bg-primary,
        .btn-primary {
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: #6b7280;
            /* gray-500 */
            opacity: 1;
        }

        .dropzone {
            background-color: #ffffff;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="nav-header">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                @if(setting('logo_path'))
                    <img src="{{ asset(setting('logo_path')) }}" alt="Logo"
                        style="max-height: 52px; max-width: 52px; object-fit: contain;">
                @else
                    <svg class="logo-abbr" width="52" height="52" viewBox="0 0 52 52" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M43.6868 12.7616C40.1151 12.2824 37.3929 9.08842 37.4346 5.5015C37.4378 4.67971 36.7515 3.99084 35.9297 3.99084H16.0768C15.2549 3.99084 14.5686 4.6798 14.5718 5.50176C14.6154 9.2348 11.8602 12.1604 8.27235 12.7689C7.55967 12.8877 7.03203 13.4961 7.01529 14.2184C6.87192 20.3982 7.73739 26.0092 9.58742 30.8954C11.0817 34.8418 13.2159 38.3236 15.9312 41.244C20.5868 46.2516 25.3291 47.8676 25.5287 47.9339C25.8821 48.0512 26.2736 48.0324 26.6139 47.8813C26.8091 47.7946 31.4487 45.6988 36.0396 40.4854L33.7807 38.4962C30.5157 42.204 27.1941 44.1849 25.9274 44.8604C24.6586 44.319 21.3888 42.6938 18.1355 39.1945C12.8056 33.4615 10.0074 25.2828 10.0102 15.4863C13.9686 14.4225 16.9309 11.0547 17.4877 7.00084H34.519C35.0754 11.0521 38.0342 14.4179 41.9885 15.4841C41.9391 21.8543 40.5621 27.6001 37.8898 32.5794L40.5418 34.0028C43.628 28.2524 45.1251 21.5986 44.9916 14.226C44.978 13.4826 44.4237 12.8606 43.6868 12.7616Z"
                            fill="url(#paint0_linear)" />
                        <path
                            d="M27.5047 20.3571H24.4948V23.5551H21.2968V26.565H24.4948V29.763H27.5047V26.565H30.7027V23.5551H27.5047V20.3571Z"
                            fill="#04A547" />
                        <path
                            d="M25.9998 14.7053C20.2948 14.7053 15.6533 19.3504 15.6533 25.0601C15.6533 30.7698 20.2948 35.4149 25.9998 35.4149C31.7048 35.4149 36.3463 30.7698 36.3463 25.0601C36.3463 19.3504 31.7048 14.7053 25.9998 14.7053ZM25.9998 32.405C21.9544 32.405 18.6632 29.11 18.6632 25.0601C18.6632 21.0101 21.9544 17.7151 25.9998 17.7151C30.0452 17.7151 33.3364 21.0101 33.3364 25.0601C33.3364 29.11 30.0452 32.405 25.9998 32.405Z"
                            fill="#CFE9DA" />
                        <defs>
                            <linearGradient id="paint0_linear" x1="15" y1="3.99994" x2="45" y2="54.4999"
                                gradientUnits="userSpaceOnUse">
                                <stop offset="1" stop-color="#1C9850" />
                                <stop offset="1" stop-color="#73FFAD" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <svg class="brand-title" width="87" height="27" viewBox="0 0 87 27" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.412 20.816V26H0.448V0.439999H18.088V5.624H6.352V10.592H16.432V15.38H6.352V20.816H18.412ZM21.9988 26V0.439999H33.5188C34.7188 0.439999 35.8228 0.691999 36.8308 1.196C37.8628 1.676 38.7508 2.336 39.4948 3.176C40.2388 3.992 40.8148 4.916 41.2228 5.948C41.6548 6.98 41.8708 8.024 41.8708 9.08C41.8708 10.664 41.4868 12.128 40.7188 13.472C39.9748 14.792 38.9668 15.8 37.6948 16.496L43.3108 26H36.7948L31.8988 17.756H27.9028V26H21.9988ZM27.9028 12.608H33.3028C33.9988 12.608 34.5988 12.284 35.1028 11.636C35.6068 10.964 35.8588 10.112 35.8588 9.08C35.8588 8.048 35.5708 7.22 34.9948 6.596C34.4188 5.948 33.7948 5.624 33.1228 5.624H27.9028V12.608ZM64.08 20.816V26H46.116V0.439999H63.756V5.624H52.02V10.592H62.1V15.38H52.02V20.816H64.08ZM83.0028 7.928C82.9308 7.832 82.6788 7.652 82.2468 7.388C81.8148 7.124 81.2748 6.848 80.6268 6.56C79.9788 6.248 79.2708 5.996 78.5028 5.804C77.7348 5.588 76.9668 5.48 76.1988 5.48C74.0868 5.48 73.0308 6.188 73.0308 7.604C73.0308 8.468 73.4868 9.08 74.3988 9.44C75.3348 9.8 76.6668 10.232 78.3948 10.736C80.0268 11.192 81.4308 11.72 82.6068 12.32C83.8068 12.896 84.7308 13.676 85.3788 14.66C86.0268 15.62 86.3508 16.892 86.3508 18.476C86.3508 19.916 86.0868 21.14 85.5587 22.148C85.0308 23.132 84.3108 23.936 83.3987 24.56C82.4868 25.16 81.4548 25.604 80.3028 25.892C79.1748 26.156 77.9988 26.288 76.7748 26.288C74.8788 26.288 72.9588 26.012 71.0148 25.46C69.0708 24.884 67.3548 24.104 65.8668 23.12L68.4588 17.972C68.5548 18.092 68.8668 18.32 69.3948 18.656C69.9228 18.968 70.5828 19.304 71.3748 19.664C72.1668 20.024 73.0308 20.336 73.9668 20.6C74.9268 20.84 75.8988 20.96 76.8828 20.96C78.9708 20.96 80.0148 20.324 80.0148 19.052C80.0148 18.404 79.7388 17.9 79.1868 17.54C78.6588 17.156 77.9268 16.832 76.9908 16.568C76.0548 16.28 74.9988 15.956 73.8228 15.596C71.4708 14.876 69.7308 14 68.6028 12.968C67.4988 11.936 66.9468 10.46 66.9468 8.54C66.9468 6.74 67.3668 5.228 68.2068 4.004C69.0708 2.756 70.2228 1.82 71.6628 1.196C73.1028 0.547999 74.6748 0.223999 76.3788 0.223999C78.1788 0.223999 79.8828 0.535999 81.4908 1.16C83.0988 1.76 84.4668 2.384 85.5948 3.032L83.0028 7.928Z"
                            fill="#3D9662" />
                    </svg>
                @endif
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        {{-- <div class="chatbox">
            <div class="chatbox-close"></div>
            <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item w-100">
                        <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="chat" role="tabpanel">
                        <div class="card mb-sm-3 mb-md-0 contacts_card dz-chat-user-box">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
                                                x="4" y="11" width="16" height="2" rx="1" />
                                        </g>
                                    </svg>
                                </a>
                                <div>
                                    <h6 class="mb-1">Chat List</h6>
                                    <p class="mb-0">Show All</p>
                                </div>
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" cx="5" cy="12" r="2" />
                                            <circle fill="#000000" cx="12" cy="12" r="2" />
                                            <circle fill="#000000" cx="19" cy="12" r="2" />
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body">
                                <ul class="contacts">
                                    <li class="name-first-letter">A</li>
                                    <li class="active dz-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="public/images/avatar/1.jpg" class="rounded-circle user_img"
                                                    alt="" />
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Archie Parker</span>
                                                <p>Kalid is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dz-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="public/images/avatar/2.jpg" class="rounded-circle user_img"
                                                    alt="" />
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Alfie Mason</span>
                                                <p>Taherah left 7 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card chat dz-chat-history-box d-none">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);" class="dz-chat-history-back">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
                                                x="14" y="7" width="2" height="10" rx="1" />
                                            <path
                                                d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                                                fill="#000000" fill-rule="nonzero"
                                                transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) " />
                                        </g>
                                    </svg>
                                </a>
                                <div>
                                    <h6 class="mb-1">Chat with Khelesh</h6>
                                    <p class="mb-0 text-success">Online</p>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <circle fill="#000000" cx="5" cy="12" r="2" />
                                                <circle fill="#000000" cx="12" cy="12" r="2" />
                                                <circle fill="#000000" cx="19" cy="12" r="2" />
                                            </g>
                                        </svg>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li class="dropdown-item">
                                            <i class="fa fa-user-circle text-primary me-2"></i> View
                                            profile
                                        </li>
                                        <li class="dropdown-item">
                                            <i class="fa fa-users text-primary me-2"></i> Add to
                                            close friends
                                        </li>
                                        <li class="dropdown-item">
                                            <i class="fa fa-plus text-primary me-2"></i> Add to
                                            group
                                        </li>
                                        <li class="dropdown-item">
                                            <i class="fa fa-ban text-primary me-2"></i> Block
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body msg_card_body dz-scroll" id="DZ_W_Contacts_Body3">
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        Hi, how are you samim?
                                        <span class="msg_time">8:40 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Hi Khalid i am good tnx how about you?
                                        <span class="msg_time_send">8:55 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        I am good too, thank you for your chat template
                                        <span class="msg_time">9:00 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        You are welcome
                                        <span class="msg_time_send">9:05 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        I am looking for your next templates
                                        <span class="msg_time">9:07 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Ok, thank you have a good day
                                        <span class="msg_time_send">9:10 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        Bye, see you
                                        <span class="msg_time">9:12 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        Hi, how are you samim?
                                        <span class="msg_time">8:40 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Hi Khalid i am good tnx how about you?
                                        <span class="msg_time_send">8:55 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        I am good too, thank you for your chat template
                                        <span class="msg_time">9:00 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        You are welcome
                                        <span class="msg_time_send">9:05 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        I am looking for your next templates
                                        <span class="msg_time">9:07 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Ok, thank you have a good day
                                        <span class="msg_time_send">9:10 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="public/images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="" />
                                    </div>
                                    <div class="msg_cotainer">
                                        Bye, see you
                                        <span class="msg_time">9:12 AM, Today</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer type_msg">
                                <div class="input-group">
                                    <textarea class="form-control" placeholder="Type your message..."></textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary">
                                            <i class="fa fa-location-arrow"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            {{-- <div class="dashboard_bar">Dashboard</div> --}}
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link ai-icon" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                    <svg width="25" height="25" viewBox="0 0 26 26" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13 1.625a1 1 0 0 1 1 1v1.005c2.19.34 4.184 1.407 5.657 3.017C21.167 8.398 22 10.35 22 12.38V15h1.5a2.5 2.5 0 1 1 0 5H16.7c-.33 1.722-1.84 3-3.7 3s-3.37-1.278-3.7-3H2.5a2.5 2.5 0 0 1 0-5H4v-2.62c0-2.03.833-3.982 2.343-5.734C7.816 5.037 9.81 3.97 12 3.63V2.625a1 1 0 0 1 1-1Zm-8 13.375V12.38c0-3.985 3.088-7.255 7-7.255s7 3.27 7 7.255V15h-14Zm8 7c.66 0 1.218-.427 1.414-1h-2.828c.196.573.755 1 1.414 1Zm9.5-3.5a.5.5 0 1 0 0-1H2.5a.5.5 0 0 0 0 1h19Z"
                                            fill="#36C95F" />
                                    </svg>
                                    <span
                                        class="badge light text-white bg-primary rounded-circle">{{ $adminNotificationUnread ?? 0 }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow"
                                    style="width: 360px; max-width: 92vw;">
                                    <div
                                        class="px-3 py-3 border-bottom d-flex align-items-center justify-content-between bg-primary text-white rounded-top">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bell me-2"></i>
                                            <span class="fw-semibold">Notifications</span>
                                        </div>
                                        @if($adminNotificationUnread ?? 0)
                                            <span class="badge bg-white text-primary">{{ $adminNotificationUnread }}</span>
                                        @endif
                                    </div>

                                    <div class="list-group list-group-flush"
                                        style="max-height: 380px; overflow-y: auto;">
                                        @if(!empty($adminNotificationLatest) && count($adminNotificationLatest))
                                            @foreach($adminNotificationLatest as $note)
                                                <a href="{{ route('admin.notifications.go', $note) }}"
                                                    class="list-group-item list-group-item-action p-3 border-0 d-flex align-items-start gap-3"
                                                    style="white-space: normal;">
                                                    <div class="flex-shrink-0">
                                                        <span
                                                            class="d-inline-flex align-items-center justify-content-center rounded-circle {{ $note->is_read ? 'bg-light' : 'bg-primary' }}"
                                                            style="width: 38px; height: 38px;">
                                                            <i
                                                                class="fas fa-{{ str_contains($note->type, 'deposit') ? 'money-bill-wave' : (str_contains($note->type, 'withdrawal') ? 'wallet' : 'bell') }} {{ $note->is_read ? 'text-muted' : 'text-white' }}"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <h6 class="mb-1 {{ $note->is_read ? 'text-muted' : 'text-dark' }}"
                                                                style="font-size: 14px; font-weight: 700;">
                                                                {{ $note->title }}
                                                            </h6>
                                                            @if(!$note->is_read)
                                                                <span class="badge bg-danger rounded-circle p-1"
                                                                    style="width: 10px; height: 10px;"></span>
                                                            @endif
                                                        </div>
                                                        <p class="mb-2 text-secondary"
                                                            style="font-size: 13px; line-height: 1.45;">
                                                            {{ \Illuminate\Support\Str::limit($note->message, 80) }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted" style="font-size: 11px;"><i
                                                                    class="fas fa-user me-1"></i>{{ $note->user?->name ?? 'User' }}</small>
                                                            <small class="text-muted" style="font-size: 11px;"><i
                                                                    class="far fa-clock me-1"></i>{{ $note->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No notifications yet</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($adminNotificationLatest) && count($adminNotificationLatest))
                                        <div
                                            class="px-3 py-2 border-top d-flex align-items-center justify-content-end gap-2">
                                            <form method="POST" action="{{ route('admin.notifications.read-all') }}"
                                                class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light text-muted">
                                                    <i class="fas fa-check me-1"></i>Mark all read
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.notifications.index') }}"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="fas fa-eye me-1"></i>View all
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </li>
                            {{-- <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell bell-link ai-icon" href="javascript:;">
                                    <svg width="23" height="28" viewBox="0 0 23 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20.4604 0.848846H3.31682C2.64742 0.849582 2.00565 1.11583 1.53231 1.58916C1.05897 2.0625 0.792727 2.70427 0.791992 3.37367V15.1562C0.792727 15.8256 1.05897 16.4674 1.53231 16.9407C2.00565 17.414 2.64742 17.6803 3.31682 17.681C3.53999 17.6812 3.75398 17.7699 3.91178 17.9277C4.06958 18.0855 4.15829 18.2995 4.15843 18.5226V20.3168C4.15843 20.6214 4.24112 20.9204 4.39768 21.1817C4.55423 21.4431 4.77879 21.6571 5.04741 21.8008C5.31602 21.9446 5.61861 22.0127 5.92292 21.998C6.22723 21.9833 6.52183 21.8863 6.77533 21.7173L12.6173 17.8224C12.7554 17.7299 12.9179 17.6807 13.0841 17.681H17.187C17.7383 17.68 18.2742 17.4993 18.7136 17.1664C19.1531 16.8334 19.472 16.3664 19.6222 15.8359L22.8965 4.05007C22.9998 3.67478 23.0152 3.28071 22.9413 2.89853C22.8674 2.51634 22.7064 2.15636 22.4707 1.8466C22.2349 1.53684 21.9309 1.28565 21.5822 1.1126C21.2336 0.93954 20.8497 0.849282 20.4604 0.848846ZM21.2732 3.60301L18.0005 15.3847C17.9499 15.5614 17.8432 15.7168 17.6964 15.8274C17.5496 15.938 17.3708 15.9979 17.187 15.9978H13.0841C12.5855 15.9972 12.098 16.1448 11.6836 16.4219L5.84165 20.3168V18.5226C5.84091 17.8532 5.57467 17.2115 5.10133 16.7381C4.62799 16.2648 3.98622 15.9985 3.31682 15.9978C3.09365 15.9977 2.87966 15.909 2.72186 15.7512C2.56406 15.5934 2.47534 15.3794 2.47521 15.1562V3.37367C2.47534 3.15051 2.56406 2.93652 2.72186 2.77871C2.87966 2.62091 3.09365 2.5322 3.31682 2.53206H20.4604C20.5905 2.53239 20.7187 2.56274 20.8352 2.62073C20.9516 2.67872 21.0531 2.7628 21.1318 2.86643C21.2104 2.97005 21.2641 3.09042 21.2886 3.21818C21.3132 3.34594 21.3079 3.47763 21.2732 3.60301Z"
                                            fill="#36C95F" />
                                        <path
                                            d="M5.84161 8.42333H10.0497C10.2729 8.42333 10.4869 8.33466 10.6448 8.17683C10.8026 8.019 10.8913 7.80493 10.8913 7.58172C10.8913 7.35851 10.8026 7.14445 10.6448 6.98661C10.4869 6.82878 10.2729 6.74011 10.0497 6.74011H5.84161C5.6184 6.74011 5.40433 6.82878 5.2465 6.98661C5.08867 7.14445 5 7.35851 5 7.58172C5 7.80493 5.08867 8.019 5.2465 8.17683C5.40433 8.33466 5.6184 8.42333 5.84161 8.42333Z"
                                            fill="#36C95F" />
                                        <path
                                            d="M13.4161 10.1065H5.84161C5.6184 10.1065 5.40433 10.1952 5.2465 10.353C5.08867 10.5109 5 10.7249 5 10.9481C5 11.1714 5.08867 11.3854 5.2465 11.5433C5.40433 11.7011 5.6184 11.7898 5.84161 11.7898H13.4161C13.6393 11.7898 13.8534 11.7011 14.0112 11.5433C14.169 11.3854 14.2577 11.1714 14.2577 10.9481C14.2577 10.7249 14.169 10.5109 14.0112 10.353C13.8534 10.1952 13.6393 10.1065 13.4161 10.1065Z"
                                            fill="#36C95F" />
                                    </svg>
                                    <span class="badge light text-white bg-primary">5</span>
                                </a>
                            </li> --}}

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
                                    <i id="icon-light" class="fas fa-sun"></i>
                                    <i id="icon-dark" class="fas fa-moon"></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                @php
                                    $adminUser = auth('admin')->user();
                                    $displayName = $adminUser->name ?? $adminUser->username ?? 'Admin';
                                @endphp
                                <a class="nav-link" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                    @if($adminUser->avatar)
                                        <img src="{{ asset('uploads/avatar/' . $adminUser->avatar) }}" width="32"
                                            height="32" class="rounded-circle" alt="Admin Avatar" />
                                    @else
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                                            style="width: 32px; height: 32px;">
                                            <i class="fas fa-user" style="font-size: 16px;"></i>
                                        </div>
                                    @endif
                                    <div class="header-info">
                                        <span>Hello,<strong> {{ $displayName }}</strong></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('admin.profile.index') }}" class="dropdown-item ai-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
                                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <span class="ms-2">Profile</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item ai-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18"
                                                height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                                <polyline points="16 17 21 12 16 7" />
                                                <line x1="21" y1="12" x2="9" y2="12" />
                                            </svg>
                                            <span class="ms-2">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        @include('admin.layouts.sidebar')

        <div class="content-body">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <div class="footer">
            <div class="copyright">
                <p>
                    Copyright © {{ now()->year }} All rights reserved.
                </p>
            </div>
        </div>





    </div>

    <script src="{{ asset('admin/vendor/global/global.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/moment/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/chart.js/Chart.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/owl-carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/apexchart/apexchart.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/dashboard/dashboard-1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/custom.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/deznav-init.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/demo.js') }}" type="text/javascript"></script>
    {{--
    <script src="{{ asset('admin/js/styleSwitcher.js') }}" type="text/javascript"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.4.2/build/ckeditor.js"></script>

    @stack('scripts')

    <!-- PHP Flasher -->
    @flasher_render
</body>

</html>