<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laravel API Tools</title>


    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;

            width: 100%;
            min-height: 100%;

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f3f4f6;

            color: #111827;
        }


        body {
            padding: 25px;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .app {
            width: 100%;
            max-width: 1500px;

            margin: auto;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .top-header {

            background:
                linear-gradient(
                    135deg,
                    #111827,
                    #1e293b
                );

            color: white;

            padding: 25px;

            border-radius: 16px;

            margin-bottom: 18px;

            box-shadow:
                0 10px 30px
                rgba(15, 23, 42, .15);
        }


        .top-header h1 {
            margin: 0 0 6px;

            font-size: 28px;

            font-weight: 800;
        }


        .top-header p {
            margin: 0;

            color: #94a3b8;

            font-size: 14px;
        }


        /* =====================================================
           TOKEN
        ===================================================== */

        .token-panel {

            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 14px;

            padding: 18px;

            margin-bottom: 18px;

            box-shadow:
                0 4px 20px
                rgba(15, 23, 42, .05);
        }


        .token-title {

            font-size: 14px;

            font-weight: 800;

            margin-bottom: 8px;
        }


        .token-row {

            display: flex;

            gap: 8px;
        }


        .token-input {

            flex: 1;

            min-width: 0;

            height: 42px;

            padding: 0 13px;

            border:
                1px solid #d1d5db;

            border-radius: 8px;

            outline: none;

            font-family:
                Consolas,
                monospace;

            font-size: 13px;
        }


        .token-input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, .1);
        }


        .save-token {

            height: 42px;

            padding:
                0 15px;

            border: none;

            border-radius: 8px;

            background: #2563eb;

            color: white;

            font-weight: 700;

            cursor: pointer;
        }


        .save-token:hover {
            background: #1d4ed8;
        }


        .clear-token {

            height: 42px;

            padding:
                0 15px;

            border: none;

            border-radius: 8px;

            background: #f1f5f9;

            color: #475569;

            font-weight: 700;

            cursor: pointer;
        }


        .clear-token:hover {
            background: #e2e8f0;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .search-panel {

            background: white;

            border:
                1px solid #e5e7eb;

            border-radius: 14px;

            padding: 14px;

            margin-bottom: 18px;
        }


        .search-input {

            width: 100%;

            height: 42px;

            border:
                1px solid #d1d5db;

            border-radius: 8px;

            padding:
                0 13px;

            outline: none;

            font-size: 14px;
        }


        .search-input:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, .1);
        }


        /* =====================================================
           SECTION
        ===================================================== */

        .section {

            background: white;

            border:
                1px solid #e5e7eb;

            border-radius: 14px;

            margin-bottom: 16px;

            overflow: hidden;

            box-shadow:
                0 4px 20px
                rgba(15, 23, 42, .04);
        }


        .section-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                15px 18px;

            background: #f8fafc;

            border-bottom:
                1px solid #e5e7eb;
        }


        .section-title {

            display: flex;

            align-items: center;

            gap: 10px;

            font-size: 16px;

            font-weight: 800;
        }


        .section-count {

            font-size: 11px;

            padding:
                4px 8px;

            border-radius: 20px;

            background: #e2e8f0;

            color: #475569;

            font-weight: 800;
        }


        /* =====================================================
           API ITEM
        ===================================================== */

        .api-item {

            border-bottom:
                1px solid #edf0f3;
        }


        .api-item:last-child {
            border-bottom: none;
        }


        .api-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding:
                13px 15px;
        }


        .api-header:hover {
            background: #fafafa;
        }


        .api-info {

            min-width: 0;

            flex: 1;

            display: flex;

            align-items: center;

            gap: 10px;

            flex-wrap: wrap;
        }


        /* =====================================================
           METHOD
        ===================================================== */

        .method {

            width: 58px;

            height: 26px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            border-radius: 6px;

            font-family:
                Consolas,
                monospace;

            font-size: 11px;

            font-weight: 900;

            flex-shrink: 0;
        }


        .method-get {

            background: #dcfce7;

            color: #15803d;
        }


        .method-post {

            background: #dbeafe;

            color: #1d4ed8;
        }


        .method-put {

            background: #fef3c7;

            color: #b45309;
        }


        .method-delete {

            background: #fee2e2;

            color: #dc2626;
        }


        /* =====================================================
           API NAME
        ===================================================== */

        .api-name {

            font-size: 13px;

            font-weight: 700;

            color: #111827;
        }


        .endpoint {

            width: 100%;

            padding-left: 68px;

            margin-top: -4px;

            color: #64748b;

            font-family:
                Consolas,
                monospace;

            font-size: 12px;

            word-break: break-all;
        }


        /* =====================================================
           ACTIONS
        ===================================================== */

        .actions {

            display: flex;

            align-items: center;

            gap: 6px;

            flex-shrink: 0;
        }


        .id-input {

            width: 75px;

            height: 34px;

            padding:
                0 9px;

            border:
                1px solid #d1d5db;

            border-radius: 7px;

            outline: none;

            font-size: 12px;
        }


        .id-input:focus {

            border-color: #2563eb;
        }


        .body-btn,
        .response-btn {

            height: 34px;

            padding:
                0 11px;

            border: none;

            border-radius: 7px;

            background: #f1f5f9;

            color: #475569;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;
        }


        .body-btn:hover,
        .response-btn:hover {

            background: #e2e8f0;
        }


        .send-btn {

            height: 34px;

            min-width: 55px;

            border: none;

            border-radius: 7px;

            color: white;

            font-family:
                Consolas,
                monospace;

            font-size: 11px;

            font-weight: 900;

            cursor: pointer;
        }


        .send-get {
            background: #16a34a;
        }


        .send-get:hover {
            background: #15803d;
        }


        .send-post {
            background: #2563eb;
        }


        .send-post:hover {
            background: #1d4ed8;
        }


        .send-put {
            background: #d97706;
        }


        .send-put:hover {
            background: #b45309;
        }


        .send-delete {
            background: #dc2626;
        }


        .send-delete:hover {
            background: #b91c1c;
        }


        /* =====================================================
           REQUEST BODY
        ===================================================== */

        .request-body {

            display: none;

            padding:
                15px 18px;

            background: #f8fafc;

            border-top:
                1px solid #e5e7eb;
        }


        .request-body.open {
            display: block;
        }


        .request-title {

            font-size: 12px;

            font-weight: 800;

            color: #475569;

            margin-bottom: 8px;
        }


        .json-input {

            width: 100%;

            min-height: 170px;

            resize: vertical;

            padding: 14px;

            background: #0f172a;

            color: #e2e8f0;

            border:
                1px solid #1e293b;

            border-radius: 9px;

            outline: none;

            font-family:
                Consolas,
                Monaco,
                monospace;

            font-size: 13px;

            line-height: 1.6;
        }


        .json-input:focus {

            border-color: #2563eb;
        }


        /* =====================================================
           RESPONSE
        ===================================================== */

        .response {

            display: none;

            border-top:
                1px solid #e5e7eb;

            background: #0b1120;
        }


        .response.open {
            display: block;
        }


        /* =====================================================
           RESPONSE HEADER
        ===================================================== */

        .response-top {

            height: 50px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0 14px;

            background: #111827;

            border-bottom:
                1px solid #1f2937;
        }


        .response-left {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .response-status {

            font-size: 12px;

            font-weight: 800;

            padding:
                5px 9px;

            border-radius: 6px;
        }


        .status-success {

            color: #22c55e;

            background:
                rgba(34, 197, 94, .1);
        }


        .status-error {

            color: #ef4444;

            background:
                rgba(239, 68, 68, .1);
        }


        .status-loading {

            color: #f59e0b;

            background:
                rgba(245, 158, 11, .1);
        }


        .response-meta {

            color: #64748b;

            font-size: 12px;
        }


        .copy-btn {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            background: #1e293b;

            color: #cbd5e1;

            border:
                1px solid #334155;

            padding:
                6px 10px;

            border-radius: 6px;

            font-size: 12px;

            cursor: pointer;
        }


        .copy-btn:hover {

            background: #334155;

            color: white;
        }


        /* =====================================================
           RESPONSE BODY
        ===================================================== */

        .response-body {

            height: 430px;

            overflow: auto;

            background: #0b1120;

            padding: 0;
        }


        .json-container {

            display: flex;

            min-width: max-content;

            font-family:
                "JetBrains Mono",
                "Fira Code",
                Consolas,
                Monaco,
                monospace;

            font-size: 13px;

            line-height: 1.7;
        }


        /* =====================================================
           LINE NUMBERS
        ===================================================== */

        .line-numbers {

            user-select: none;

            text-align: right;

            padding:
                18px 14px;

            color: #475569;

            background: #0f172a;

            border-right:
                1px solid #1e293b;

            min-width: 55px;

            white-space: pre;

            flex-shrink: 0;
        }


        /* =====================================================
           CODE
        ===================================================== */

        .code-area {

            padding:
                18px 20px;

            color: #e2e8f0;

            white-space: pre;

            min-width: 700px;
        }


        /* =====================================================
           JSON COLORS
        ===================================================== */

        .json-key {
            color: #7dd3fc;
        }


        .json-string {
            color: #86efac;
        }


        .json-number {
            color: #fbbf24;
        }


        .json-boolean {
            color: #c084fc;
        }


        .json-null {
            color: #f87171;
        }


        .json-punctuation {
            color: #94a3b8;
        }


        /* =====================================================
           SCROLLBAR
        ===================================================== */

        .response-body::-webkit-scrollbar {

            width: 9px;

            height: 9px;
        }


        .response-body::-webkit-scrollbar-track {

            background: #0b1120;
        }


        .response-body::-webkit-scrollbar-thumb {

            background: #334155;

            border-radius: 10px;
        }


        .response-body::-webkit-scrollbar-thumb:hover {

            background: #475569;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .empty {

            padding: 40px;

            text-align: center;

            color: #64748b;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media(max-width: 800px) {

            body {
                padding: 10px;
            }


            .api-header {

                align-items: flex-start;

                flex-direction: column;
            }


            .actions {

                width: 100%;

                flex-wrap: wrap;
            }


            .endpoint {

                padding-left: 0;
            }


            .token-row {

                flex-direction: column;
            }


            .save-token,
            .clear-token {

                width: 100%;
            }

        }

    </style>

</head>


<body>


<div class="app">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="top-header">

        <h1>
            Laravel API Tools
        </h1>

        <p>
            API testing dashboard • GET • POST • PUT • DELETE
        </p>

    </div>



    {{-- =====================================================
         TOKEN
    ====================================================== --}}

    <div class="token-panel">

        <div class="token-title">

            🔐 Sanctum Bearer Token

        </div>


        <div class="token-row">

            <input
                type="text"
                id="token"
                class="token-input"
                placeholder="Paste your Sanctum token here..."
            >


            <button
                type="button"
                class="save-token"
                onclick="saveToken()"
            >

                Save Token

            </button>


            <button
                type="button"
                class="clear-token"
                onclick="clearToken()"
            >

                Clear

            </button>

        </div>

    </div>



    {{-- =====================================================
         SEARCH
    ====================================================== --}}

    <div class="search-panel">

        <input
            type="text"
            id="routeSearch"
            class="search-input"
            placeholder="Search API route... example: products, users, orders"
            oninput="searchRoutes()"
        >

    </div>



    @php

        $apiSections = [

            /*
            |--------------------------------------------------------------------------
            | PUBLIC
            |--------------------------------------------------------------------------
            */

            'PUBLIC' => [

                [
                    'method' => 'POST',
                    'name' => 'Register',
                    'url' => '/api/v1/register',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Login',
                    'url' => '/api/v1/login',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Forgot Password',
                    'url' => '/api/v1/forgot-password',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Reset Password',
                    'url' => '/api/v1/reset-password',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Recovery Account',
                    'url' => '/api/v1/recovery-account',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Recovery Account By Phone',
                    'url' => '/api/v1/recovery-account-by-phone',
                    'body' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Bakong Webhook',
                    'url' => '/api/v1/bakong/webhook',
                    'body' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | AUTHENTICATED
            |--------------------------------------------------------------------------
            */

            'AUTHENTICATED' => [

                [
                    'method' => 'GET',
                    'name' => 'Current User',
                    'url' => '/api/v1/user',
                ],

                [
                    'method' => 'POST',
                    'name' => 'Logout',
                    'url' => '/api/v1/logout',
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | PRODUCTS
            |--------------------------------------------------------------------------
            */

            'PRODUCTS' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Products',
                    'url' => '/api/v1/products',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Product By ID',
                    'url' => '/api/v1/products/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Product',
                    'url' => '/api/v1/products',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Product',
                    'url' => '/api/v1/products/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Product',
                    'url' => '/api/v1/products/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | CATEGORIES
            |--------------------------------------------------------------------------
            */

            'CATEGORIES' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Categories',
                    'url' => '/api/v1/categories',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Category By ID',
                    'url' => '/api/v1/categories/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Category',
                    'url' => '/api/v1/categories',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Category',
                    'url' => '/api/v1/categories/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Category',
                    'url' => '/api/v1/categories/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | CATEGORY IMAGES
            |--------------------------------------------------------------------------
            */

            'CATEGORY IMAGES' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Category Images',
                    'url' => '/api/v1/categories-images',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Category Image By ID',
                    'url' => '/api/v1/categories-images/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Category Image',
                    'url' => '/api/v1/categories-images',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Category Image',
                    'url' => '/api/v1/categories-images/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Category Image',
                    'url' => '/api/v1/categories-images/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | PRODUCT IMAGES
            |--------------------------------------------------------------------------
            */

            'PRODUCT IMAGES' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Product Images',
                    'url' => '/api/v1/products-images',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Product Image By ID',
                    'url' => '/api/v1/products-images/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Product Image',
                    'url' => '/api/v1/products-images',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Product Image',
                    'url' => '/api/v1/products-images/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Product Image',
                    'url' => '/api/v1/products-images/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | WARRANTIES
            |--------------------------------------------------------------------------
            */

            'WARRANTIES' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Warranties',
                    'url' => '/api/v1/warranties',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Warranty By ID',
                    'url' => '/api/v1/warranties/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Warranty',
                    'url' => '/api/v1/warranties',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Warranty',
                    'url' => '/api/v1/warranties/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Warranty',
                    'url' => '/api/v1/warranties/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | SLIDE SHOWS
            |--------------------------------------------------------------------------
            */

            'SLIDE SHOWS' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Slide Shows',
                    'url' => '/api/v1/slide-shows',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Slide Show By ID',
                    'url' => '/api/v1/slide-shows/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Slide Show',
                    'url' => '/api/v1/slide-shows',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Slide Show',
                    'url' => '/api/v1/slide-shows/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Slide Show',
                    'url' => '/api/v1/slide-shows/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | SLIDE SHOW IMAGES
            |--------------------------------------------------------------------------
            */

            'SLIDE SHOW IMAGES' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Slide Show Images',
                    'url' => '/api/v1/slide-show-images',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Slide Show Image By ID',
                    'url' => '/api/v1/slide-show-images/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Slide Show Image',
                    'url' => '/api/v1/slide-show-images',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Slide Show Image',
                    'url' => '/api/v1/slide-show-images/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Slide Show Image',
                    'url' => '/api/v1/slide-show-images/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | STOCKS
            |--------------------------------------------------------------------------
            */

            'STOCKS' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Stocks',
                    'url' => '/api/v1/stocks',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Stock By ID',
                    'url' => '/api/v1/stocks/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Stock',
                    'url' => '/api/v1/stocks',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Stock',
                    'url' => '/api/v1/stocks/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Stock',
                    'url' => '/api/v1/stocks/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | COMPANY INFO
            |--------------------------------------------------------------------------
            */

            'COMPANY INFO' => [

                [
                    'method' => 'GET',
                    'name' => 'Get Company Info',
                    'url' => '/api/v1/company-info',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Company Info By ID',
                    'url' => '/api/v1/company-info/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Company Info',
                    'url' => '/api/v1/company-info',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Company Info',
                    'url' => '/api/v1/company-info/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Company Info',
                    'url' => '/api/v1/company-info/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | ROLES
            |--------------------------------------------------------------------------
            */

            'ROLES - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Roles',
                    'url' => '/api/v1/roles',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Role By ID',
                    'url' => '/api/v1/roles/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Role',
                    'url' => '/api/v1/roles',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Role',
                    'url' => '/api/v1/roles/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Role',
                    'url' => '/api/v1/roles/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            'USERS - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Users',
                    'url' => '/api/v1/users',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get User By ID',
                    'url' => '/api/v1/users/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create User',
                    'url' => '/api/v1/users',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update User',
                    'url' => '/api/v1/users/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete User',
                    'url' => '/api/v1/users/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | CUSTOMERS
            |--------------------------------------------------------------------------
            */

            'CUSTOMERS - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Customers',
                    'url' => '/api/v1/customers',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Customer By ID',
                    'url' => '/api/v1/customers/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Customer',
                    'url' => '/api/v1/customers',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Customer',
                    'url' => '/api/v1/customers/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Customer',
                    'url' => '/api/v1/customers/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | OTP
            |--------------------------------------------------------------------------
            */

            'OTP - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All OTP',
                    'url' => '/api/v1/otp',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get OTP By ID',
                    'url' => '/api/v1/otp/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create OTP',
                    'url' => '/api/v1/otp',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update OTP',
                    'url' => '/api/v1/otp/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete OTP',
                    'url' => '/api/v1/otp/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | POSTS
            |--------------------------------------------------------------------------
            */

            'POSTS - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Posts',
                    'url' => '/api/v1/posts',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Post By ID',
                    'url' => '/api/v1/posts/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Post',
                    'url' => '/api/v1/posts',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Post',
                    'url' => '/api/v1/posts/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Post',
                    'url' => '/api/v1/posts/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | POST IMAGES
            |--------------------------------------------------------------------------
            */

            'POST IMAGES - ADMIN' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Post Images',
                    'url' => '/api/v1/post-images',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Post Image By ID',
                    'url' => '/api/v1/post-images/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Post Image',
                    'url' => '/api/v1/post-images',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Post Image',
                    'url' => '/api/v1/post-images/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Post Image',
                    'url' => '/api/v1/post-images/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            'PROFILE - USER' => [

                [
                    'method' => 'GET',
                    'name' => 'Get Profile',
                    'url' => '/api/v1/profile',
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Profile',
                    'url' => '/api/v1/profile',
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Profile',
                    'url' => '/api/v1/profile',
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | ORDERS
            |--------------------------------------------------------------------------
            */

            'ORDERS - USER' => [

                [
                    'method' => 'GET',
                    'name' => 'Get My Orders',
                    'url' => '/api/v1/orders',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get Order By ID',
                    'url' => '/api/v1/orders/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Order',
                    'url' => '/api/v1/orders',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update Order',
                    'url' => '/api/v1/orders/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete Order',
                    'url' => '/api/v1/orders/{id}',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | PAYMENTS
            |--------------------------------------------------------------------------
            */

            'PAYMENTS - USER' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All Payments',
                    'url' => '/api/v1/payments',
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create Bakong Payment',
                    'url' => '/api/v1/payments/bakong/create',
                    'body' => true,
                ],

                [
                    'method' => 'GET',
                    'name' => 'Check Payment Status',
                    'url' => '/api/v1/payments/{id}/check-status',
                    'id' => true,
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | USER IMAGES
            |--------------------------------------------------------------------------
            */

            'USER IMAGES - USER' => [

                [
                    'method' => 'GET',
                    'name' => 'Get All User Images',
                    'url' => '/api/v1/user-images',
                ],

                [
                    'method' => 'GET',
                    'name' => 'Get User Image By ID',
                    'url' => '/api/v1/user-images/{id}',
                    'id' => true,
                ],

                [
                    'method' => 'POST',
                    'name' => 'Create User Image',
                    'url' => '/api/v1/user-images',
                    'body' => true,
                ],

                [
                    'method' => 'PUT',
                    'name' => 'Update User Image',
                    'url' => '/api/v1/user-images/{id}',
                    'id' => true,
                    'body' => true,
                ],

                [
                    'method' => 'DELETE',
                    'name' => 'Delete User Image',
                    'url' => '/api/v1/user-images/{id}',
                    'id' => true,
                ],

            ],

        ];

    @endphp



    {{-- =====================================================
         ROUTES
    ====================================================== --}}

    @foreach($apiSections as $sectionName => $routes)

        <div
            class="section"
            data-section="{{ strtolower($sectionName) }}"
        >


            <div class="section-header">

                <div class="section-title">

                    {{ $sectionName }}

                    <span class="section-count">

                        {{ count($routes) }}

                    </span>

                </div>

            </div>



            @foreach($routes as $index => $api)

                @php

                    $method =
                        strtoupper(
                            $api['method']
                        );

                    $hasId =
                        $api['id'] ?? false;

                    $hasBody =
                        $api['body'] ?? false;

                    $unique =
                        md5(
                            $sectionName .
                            '_' .
                            $index .
                            '_' .
                            $method .
                            '_' .
                            $api['url']
                        );

                    $responseId =
                        'response_' . $unique;

                    $requestId =
                        'request_' . $unique;

                    $idInputId =
                        'id_' . $unique;

                @endphp


                <div
                    class="api-item"
                    data-route-search="
                        {{ strtolower(
                            $api['name'] .
                            ' ' .
                            $api['url'] .
                            ' ' .
                            $method .
                            ' ' .
                            $sectionName
                        ) }}
                    "
                >


                    {{-- =================================================
                         API HEADER
                    ================================================== --}}

                    <div class="api-header">


                        <div class="api-info">


                            <span
                                class="
                                    method
                                    method-{{ strtolower($method) }}
                                "
                            >
                                {{ $method }}
                            </span>


                            <span class="api-name">

                                {{ $api['name'] }}

                            </span>


                            <span class="endpoint">

                                {{ $api['url'] }}

                            </span>


                        </div>



                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="actions">


                            @if($hasId)

                                <input
                                    type="number"
                                    id="{{ $idInputId }}"
                                    class="id-input"
                                    placeholder="ID"
                                >

                            @endif



                            @if($hasBody)

                                <button
                                    type="button"
                                    class="body-btn"
                                    onclick="
                                        toggleElement(
                                            '{{ $requestId }}'
                                        )
                                    "
                                >

                                    Body

                                </button>

                            @endif



                            <button
                                type="button"
                                class="
                                    send-btn
                                    send-{{ strtolower($method) }}
                                "
                                onclick="
                                    sendRequest(
                                        '{{ $method }}',
                                        '{{ $api['url'] }}',
                                        '{{ $responseId }}',
                                        {{ $hasId ? 'true' : 'false' }},
                                        '{{ $idInputId }}',
                                        {{ $hasBody ? 'true' : 'false' }},
                                        '{{ $requestId }}'
                                    )
                                "
                            >

                                SEND

                            </button>



                            <button
                                type="button"
                                class="response-btn"
                                onclick="
                                    toggleElement(
                                        '{{ $responseId }}'
                                    )
                                "
                            >

                                Response ▼

                            </button>


                        </div>

                    </div>



                    {{-- =================================================
                         REQUEST BODY
                    ================================================== --}}

                    @if($hasBody)

                        <div
                            id="{{ $requestId }}"
                            class="request-body"
                        >

                            <div class="request-title">

                                Request JSON

                            </div>


                            <textarea
                                id="{{ $requestId }}_input"
                                class="json-input"
                                placeholder='{
    "name": "Example",
    "email": "example@gmail.com"
}'
                            ></textarea>

                        </div>

                    @endif



                    {{-- =================================================
                         RESPONSE
                    ================================================== --}}

                    <div
                        id="{{ $responseId }}"
                        class="response"
                    >


                        <div class="response-top">


                            <div class="response-left">


                                <span
                                    class="response-status"
                                >

                                    No request yet

                                </span>


                                <span
                                    class="response-meta"
                                >

                                    Response

                                </span>


                            </div>



                            <button
                                type="button"
                                class="copy-btn"
                                onclick="
                                    copyResponse(
                                        '{{ $responseId }}'
                                    )
                                "
                            >

                                📋 Copy

                            </button>


                        </div>



                        <div
                            class="response-body"
                        >

                            <div class="json-container">


                                <div class="line-numbers">

                                    1

                                </div>


                                <div class="code-area">

                                    Click SEND to request data.

                                </div>


                            </div>

                        </div>


                    </div>


                </div>

            @endforeach

        </div>

    @endforeach


</div>



<script>


    /* =========================================================
       TOKEN
    ========================================================= */


    const tokenInput =
        document.getElementById(
            'token'
        );


    const savedToken =
        localStorage.getItem(
            'sanctum_token'
        );


    if (savedToken) {

        tokenInput.value =
            savedToken;

    }



    function saveToken() {

        const token =
            tokenInput.value.trim();


        if (!token) {

            alert(
                'Please enter your Sanctum token.'
            );

            return;

        }


        localStorage.setItem(
            'sanctum_token',
            token
        );


        alert(
            'Token saved successfully.'
        );

    }



    function clearToken() {

        localStorage.removeItem(
            'sanctum_token'
        );


        tokenInput.value = '';

    }



    /* =========================================================
       TOGGLE
    ========================================================= */


    function toggleElement(id) {

        const element =
            document.getElementById(id);


        if (!element) {
            return;
        }


        element.classList.toggle(
            'open'
        );

    }



    /* =========================================================
       SEND REQUEST
    ========================================================= */


    async function sendRequest(
        method,
        originalUrl,
        responseId,
        hasId,
        idInputId,
        hasBody,
        requestId
    ) {


        const responseBox =
            document.getElementById(
                responseId
            );


        const statusElement =
            responseBox.querySelector(
                '.response-status'
            );


        const responseBody =
            responseBox.querySelector(
                '.response-body'
            );


        /* -----------------------------------------------------
           SHOW RESPONSE
        ----------------------------------------------------- */


        responseBox.classList.add(
            'open'
        );


        /* -----------------------------------------------------
           URL
        ----------------------------------------------------- */


        let url =
            originalUrl;


        if (hasId) {


            const idInput =
                document.getElementById(
                    idInputId
                );


            const id =
                idInput.value.trim();


            if (!id) {

                showError(
                    responseBox,
                    'Please enter ID.'
                );

                return;

            }


            url =
                url.replace(
                    '{id}',
                    encodeURIComponent(id)
                );

        }



        /* -----------------------------------------------------
           TOKEN
        ----------------------------------------------------- */


        const token =
            tokenInput.value.trim();



        /* -----------------------------------------------------
           HEADERS
        ----------------------------------------------------- */


        const headers = {

            'Accept':
                'application/json',

            'Content-Type':
                'application/json',

            'X-Requested-With':
                'XMLHttpRequest'

        };


        if (token) {

            headers[
                'Authorization'
            ] =
                `Bearer ${token}`;

        }



        /* -----------------------------------------------------
           OPTIONS
        ----------------------------------------------------- */


        const options = {

            method: method,

            headers: headers

        };



        /* -----------------------------------------------------
           BODY
        ----------------------------------------------------- */


        if (
            hasBody &&
            method !== 'GET' &&
            method !== 'DELETE'
        ) {


            const textarea =
                document.getElementById(
                    requestId + '_input'
                );


            const body =
                textarea.value.trim();


            if (body) {


                try {

                    JSON.parse(body);

                } catch (error) {

                    showError(
                        responseBox,
                        'Invalid JSON: ' +
                        error.message
                    );

                    return;

                }


                options.body =
                    body;

            }

        }



        /* -----------------------------------------------------
           LOADING
        ----------------------------------------------------- */


        statusElement.className =
            'response-status status-loading';


        statusElement.innerText =
            'Loading...';


        responseBody.innerHTML = `

            <div class="json-container">

                <div class="line-numbers">
                    1
                </div>

                <div class="code-area">
                    Sending ${method} request...
                </div>

            </div>

        `;



        /* -----------------------------------------------------
           TIMER
        ----------------------------------------------------- */


        const startTime =
            performance.now();



        /* -----------------------------------------------------
           FETCH
        ----------------------------------------------------- */


        try {


            const response =
                await fetch(
                    url,
                    options
                );


            const endTime =
                performance.now();


            const duration =
                Math.round(
                    endTime -
                    startTime
                );


            /* -------------------------------------------------
               CONTENT TYPE
            ------------------------------------------------- */


            const contentType =
                response.headers.get(
                    'content-type'
                );


            let data;


            if (
                contentType &&
                contentType.includes(
                    'application/json'
                )
            ) {

                data =
                    await response.json();

            } else {

                data =
                    await response.text();

            }



            /* -------------------------------------------------
               STATUS
            ------------------------------------------------- */


            if (response.ok) {

                statusElement.className =
                    'response-status status-success';

            } else {

                statusElement.className =
                    'response-status status-error';

            }


            statusElement.innerText =
                `HTTP ${response.status}`;


            responseBox.querySelector(
                '.response-meta'
            ).innerText =
                `${response.statusText} • ${duration} ms`;



            /* -------------------------------------------------
               RENDER
            ------------------------------------------------- */


            renderResponse(
                responseBody,
                data
            );


        } catch (error) {


            showError(
                responseBox,
                error.message
            );

        }

    }



    /* =========================================================
       RENDER RESPONSE
    ========================================================= */


    function renderResponse(
        container,
        data
    ) {


        let text;


        if (
            typeof data ===
            'object'
        ) {

            text =
                JSON.stringify(
                    data,
                    null,
                    4
                );

        } else {

            text =
                String(data);

        }


        const lines =
            text.split('\n');


        const lineNumbers =
            lines
                .map(
                    (_, index) =>
                        index + 1
                )
                .join('\n');


        container.innerHTML = `

            <div class="json-container">

                <div class="line-numbers">${lineNumbers}</div>

                <div class="code-area">
                    ${highlightJson(text)}
                </div>

            </div>

        `;

    }



    /* =========================================================
       JSON HIGHLIGHTING
    ========================================================= */


    function highlightJson(
        text
    ) {


        const escaped =
            escapeHtml(text);


        /*
        ---------------------------------------------------------
        Important:
        Use ONE regex pass.

        This prevents the previous problem where generated
        <span> tags were processed again.
        ---------------------------------------------------------
        */


        const tokenRegex =
            /("(?:\\.|[^"\\])*")(\s*:)?|(-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?)|\b(true|false)\b|\b(null)\b/g;


        return escaped.replace(
            tokenRegex,
            function(
                match,
                stringValue,
                colon,
                numberValue,
                booleanValue,
                nullValue
            ) {


                /* ---------------------------------------------
                   KEY
                --------------------------------------------- */

                if (
                    stringValue &&
                    colon
                ) {

                    return (
                        '<span class="json-key">' +
                        stringValue +
                        '</span>' +
                        colon
                    );

                }


                /* ---------------------------------------------
                   STRING
                --------------------------------------------- */

                if (
                    stringValue
                ) {

                    return (
                        '<span class="json-string">' +
                        stringValue +
                        '</span>'
                    );

                }


                /* ---------------------------------------------
                   NUMBER
                --------------------------------------------- */

                if (
                    numberValue
                ) {

                    return (
                        '<span class="json-number">' +
                        numberValue +
                        '</span>'
                    );

                }


                /* ---------------------------------------------
                   BOOLEAN
                --------------------------------------------- */

                if (
                    booleanValue
                ) {

                    return (
                        '<span class="json-boolean">' +
                        booleanValue +
                        '</span>'
                    );

                }


                /* ---------------------------------------------
                   NULL
                --------------------------------------------- */

                if (
                    nullValue
                ) {

                    return (
                        '<span class="json-null">' +
                        nullValue +
                        '</span>'
                    );

                }


                return match;

            }
        );

    }



    /* =========================================================
       ESCAPE HTML
    ========================================================= */


    function escapeHtml(
        value
    ) {

        return String(value)
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }



    /* =========================================================
       ERROR
    ========================================================= */


    function showError(
        responseBox,
        message
    ) {


        responseBox.classList.add(
            'open'
        );


        const status =
            responseBox.querySelector(
                '.response-status'
            );


        const meta =
            responseBox.querySelector(
                '.response-meta'
            );


        const body =
            responseBox.querySelector(
                '.response-body'
            );


        status.className =
            'response-status status-error';


        status.innerText =
            'ERROR';


        meta.innerText =
            'Request failed';


        renderResponse(
            body,
            {
                error: message
            }
        );

    }



    /* =========================================================
       COPY
    ========================================================= */


    async function copyResponse(
        responseId
    ) {


        const responseBox =
            document.getElementById(
                responseId
            );


        const code =
            responseBox.querySelector(
                '.code-area'
            );


        if (!code) {
            return;
        }


        const text =
            code.innerText;


        try {


            await navigator.clipboard.writeText(
                text
            );


            const button =
                responseBox.querySelector(
                    '.copy-btn'
                );


            const oldText =
                button.innerText;


            button.innerText =
                '✓ Copied';


            setTimeout(
                function() {

                    button.innerText =
                        oldText;

                },
                1500
            );


        } catch (error) {

            alert(
                'Could not copy response.'
            );

        }

    }



    /* =========================================================
       SEARCH
    ========================================================= */


    function searchRoutes() {


        const searchInput =
            document.getElementById(
                'routeSearch'
            );


        const keyword =
            searchInput.value
                .toLowerCase()
                .trim();


        const items =
            document.querySelectorAll(
                '.api-item'
            );


        const sections =
            document.querySelectorAll(
                '.section'
            );


        items.forEach(
            function(item) {


                const text =
                    item
                        .getAttribute(
                            'data-route-search'
                        )
                        .toLowerCase();


                if (
                    !keyword ||
                    text.includes(keyword)
                ) {

                    item.style.display =
                        '';

                } else {

                    item.style.display =
                        'none';

                }

            }
        );


        sections.forEach(
            function(section) {


                const visibleItems =
                    section.querySelectorAll(
                        '.api-item:not([style*="display: none"])'
                    );


                if (
                    keyword &&
                    visibleItems.length === 0
                ) {

                    section.style.display =
                        'none';

                } else {

                    section.style.display =
                        '';

                }

            }
        );

    }



</script>


</body>

</html>