@extends('layouts.app')

@section('content')

@csrf

<div class="container py-5">
    <div class="d-flex align-items-center mb-5 gap-3">
        <i class="fa fa-tags fs-1 classic-color"></i>
        <div>
            <h2 class="fs-30 fw-600 ls-1">Browse categories</h2>
            <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/59/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Shoes
                        </span>
                    </div>
                    <a href="chuck-70-raw-edge" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/1/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Trousers
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/225/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Hoodies
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/83/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Sale
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/161/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            New one
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/134/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Crazy Frog
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/214/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Shoes
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/96/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Trousers
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/250/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Hoodies
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/187/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Sale
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/168/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            New one
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    <img src="https://picsum.photos/id/239/500/500" class="object-fit-cover"
                        style="height: 200px;    width: 100%;" alt="Placeholder">
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            Crazy Frog
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="block">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3">
            <i class="fa fa-tags fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1">Browse categories</h2>
                <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
            </div>
        </div>
    </div>
    <div class="items">
        <div class="row g-4">
            @foreach($products as $product)
            @include('products.product', ['product' => $product, 'col' => 3])
            @endforeach
        </div>
    </div>

    <!-- 
    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-2">
            <div class="card border-0 rounded-3 overflow-hidden d-flex flex-column" style="height: 200px;">
                <div class="position-relative flex-grow-1" style="height: 200px;">
                    @if($category->thumbnail)
                    <img src="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/storage/1/black_jeans.jpg"
                        data="{{ $category->thumbnail->getUrl() }}" class="object-fit-cover"
                        alt="{{ $category->name }}">
                    @else
                    <img src="https://picsum.photos/id/{{ rand(1, 250) }}/500/500" 
                        class="object-fit-cover" style="height: 200px;    width: 100%;" alt="Placeholder">
                    @endif
                    <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center mb-4">
                        <span
                            class="text-black fs-6 px-3 py-1 d-inline-flex align-items-center justify-content-center rounded-1 bg-light bg-opacity-75 w-auto"
                            style="min-width: 120px;">
                            {{ $category->translateAttribute('name') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


    </div> -->

</div>

@endsection