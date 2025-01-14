<?php

const REPORT_DURATION_ALL = 1;
const REPORT_DURATION_MONTHLY = 2;
const REPORT_DURATION_YEARLY = 3;
const REPORT_DURATION_CUSTOM = 4;

const INCOME_EXPENSE_TYPE_INCOME = 1;
const INCOME_EXPENSE_TYPE_EXPENSE = 2;

const PAYMENT_STATUS_PENDING = 0;
const PAYMENT_STATUS_SUCCESS = 1;
const PAYMENT_STATUS_CANCEL = 2;

const PAYMENT_TYPE_OFFLINE = 1;
const PAYMENT_TYPE_ONLINE = 2;

const PAYMENT_GATEWAY_MODE_LIVE = 1;
const PAYMENT_GATEWAY_MODE_SANDBOX = 2;

const LANGUAGE_RTL_OFF = 0;
const LANGUAGE_RTL_ON = 1;

const CLEAR_ROUTE_CACHE = 1;
const CLEAR_VIEW_CACHE = 2;
const CLEAR_CONFIG_CACHE = 3;
const CLEAR_APPLICATION_CACHE = 4;
const CLEAR_ALL_CACHE = 5;

const STORAGE_DRIVER_PUBLIC = 'public';
const STORAGE_DRIVER_AWS = 'aws';
const STORAGE_DRIVER_WASABI = 'wasabi';
const STORAGE_DRIVER_VULTR = 'vultr';

const DEFAULT_TENANT_ID_ADMIN = 'byte*intelligent';
const DEFAULT_TENANT_ID_SUPER_ADMIN = NULL;

const TEMPLATE_TYPE_NOTIFICATION = 1;
const TEMPLATE_TYPE_EMAIL = 2;

const NOTIFICATION_STATUS_UNSEEN = 0;
const NOTIFICATION_STATUS_SEEN = 1;

const STATUS_PENDING = 0;
const STATUS_DEACTIVE = 0;
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
const STATUS_DEACTIVATE = 2;
const STATUS_DISABLE = 6;
const STATUS_APPROVED = 7;
const STATUS_CANCEL = 8;


const NOTICE_FOR_ALL = 0;
const NOTICE_FOR_STUDENT = 1;
const NOTICE_FOR_INSTRUCTOR = 2;


// User Role Type
const USER_STATUS_INACTIVE = 0;
const USER_STATUS_ACTIVE = 1;
const USER_STATUS_UNVERIFIED = 2;

const USER_ROLE_SUPER_ADMIN = 1;
const USER_ROLE_ADMIN = 2;
const USER_ROLE_STAFF = 3;
const USER_ROLE_INSTRUCTOR = 4;
const USER_ROLE_STUDENT = 5;

// Message constant
// Message
const MSG_SOMETHING_WENT_WRONG = "Something went wrong! Please try again";
const MSG_CREATED_SUCCESSFULLY = "Created Successfully";
const MSG_FAVORITES_SUCCESSFULLY = "Image add to favorite list";
const MSG_FAVORITES_REMOVE_SUCCESSFULLY = "Image removed from favorite list";
const MSG_UPDATED_SUCCESSFULLY = "Updated Successfully";
const MSG_SUBMIT_SUCCESSFULLY = "Submit Successfully";
const MSG_STATUS_UPDATED_SUCCESSFULLY = "Status Updated Successfully";
const MSG_DELETED_SUCCESSFULLY = "Deleted Successfully";
const MSG_UPLOADED_SUCCESSFULLY = "Uploaded Successfully";
const MSG_DATA_FETCH_SUCCESSFULLY = "Data Fetch Successfully";
const MSG_SENT_SUCCESSFULLY = "Sent Successfully";
const MSG_PAY_SUCCESSFULLY = "Pay Successfully";
const MSG_ASSIGNED_SUCCESSFULLY = "Assigned Successfully";

const MSG_SEARCH_FOUND = "Search Found";
const MSG_SEARCH_NOT_FOUND = "No Search Found";
const DO_NOT_HAVE_PERMISSION = 7;

// Currency placement
const CURRENCY_PLACEMENT_BEFORE=1;
const CURRENCY_PLACEMENT_AFTER=2;


const ACTIVE = 1;
const INITIATE = 2;
const DEACTIVATE = 0;


const GATEWAY_MODE_LIVE = 1;
const GATEWAY_MODE_SANDBOX = 2;

//Gateway name
const PAYPAL = 'paypal';
const STRIPE = 'stripe';
const RAZORPAY = 'razorpay';
const INSTAMOJO = 'instamojo';
const MOLLIE = 'mollie';
const PAYSTACK = 'paystack';
const SSLCOMMERZ = 'sslcommerz';
const MERCADOPAGO = 'mercadopago';
const FLUTTERWAVE = 'flutterwave';
const BANK = 'bank';
const WALLET = 'wallet';
const COINBASE = 'coinbase';
const CASH = 'cash';

//Frontend settings Section id
const HERO_SECTION_ID = 1;
const TRADING_PLATFORM_SECTION_ID = 2;
const CRYPTOCURRENCY_SECTION_ID = 3;
const PAYMENT_SECTION_ID = 4;
const TRUSTED_PLATFORM_SECTION_ID = 5;
const NEWS_AND_ARTICLES_SECTION_ID = 6;
const GET_IN_TOUCH_SECTION_ID = 7;

const DURATION_TYPE_DAY = 1;
const DURATION_TYPE_MONTH = 2;
const DURATION_TYPE_YEAR = 3;
const DEPOSIT_TYPE_BUY = 1;
const DEPOSIT_TYPE_DEPOSIT = 2;

const ORDER_TYPE_DEPOSIT = 1;
const ORDER_TYPE_HARDWARE = 2;
const ORDER_TYPE_PLAN = 3;

const RETURN_TYPE_FIXED = 1;
const RETURN_TYPE_RANDOM = 2;

const PAGE_ABOUT_US=1;
const PAGE_PRIVACY_POLICY=2;
const PAGE_TERMS_OF_SERVICE=3;
const PAGE_COOKIE_POLICY=4;
const PAGE_REFUND_POLICY=5;

// gender
const GENDER_MALE = 1;
const GENDER_FEMALE = 2;
const GENDER_OTHERS = 3;

// enrolments status
const ENROLMENT_PENDING = 0;
const ENROLMENT_APPROVED = 1;
const ENROLMENT_RUNNING = 2;
const ENROLMENT_CANCEL = 3;
const ENROLMENT_COMPLEATE = 4;
const ENROLMENT_CLOSE = 5;
