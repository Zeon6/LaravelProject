<!doctype html>
<html lang="english">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Project</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="flex flex-col min-h-screen">
@include('partials.header2')
<img src="{{ $company->image }}" alt="" class="h-64 w-full object-cover">
<main class="flex-grow pl-10 pr-10 pt-2 font-bold">
    <div>
        <h1 class="text-2xl">{{ $company->name }}</h1>
        <div class="font-medium">
            <span class="pr-5">Open until: </span>
            <span class="pr-5">Rating</span>
            <span id="infoButton" class="text-blue-500 cursor-pointer">Info</span>
        </div>
    </div>
    <div>
        <span>Services:</span>
        @foreach($services as $service)
            <div class="inline-block m-5 border pl-5 pr-5 pt-2 pb-2 service-div cursor-pointer"
                 data-modal-id="serviceModal_{{ $loop->index }}">
                {{ $service->name }}
            </div>
            <div id="serviceModal_{{ $loop->index }}"
                 class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center service-modal">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
                    <div class="text-center p-4">
                        <h2 class="text-2xl">{{ $service->name }}</h2>
                        <div class="mt-4">
                            <p class="mt-2">Popis služby</p>
                            <span class="mr-4 font-medium">Cena služby: {{ $service->price }}€</span>
                            <span class="font-medium">Dĺžka služby: {{ $service->length }} minút</span>
                            <p>Voľné časy pre rezerváciu:</p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-100 flex justify-end">
                        @auth
                            <a href="{{ route('confirm.reservation', ['id' => $service->id]) }}">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded mr-4">Vytvor rezerváciu</button>
                            </a>
                        @else
                            <p class="text-red-500 font-bold">Musis byt prihlaseny aby si si mohol rezervovat sluzbu</p>
                        @endauth
                        @if($loggedInUserId === $company->user_id)
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded mr-4 edit-service"
                                    data-modal-id="editModal_{{ $loop->index }}">Upraviť službu
                            </button>
                        @endif
                        <button class="bg-red-500 text-white px-4 py-2 rounded close-modal">Zruš</button>
                    </div>
                </div>
            </div>

            <div id="editModal_{{ $loop->index }}"
                 class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center service-modal">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
                    <div class="p-4 text-center">
                        <h2 class="text-xl font-bold mb-4">Edit Service</h2>
                        <form action="{{ route('services.update', ['id' => $service->id]) }}" method="POST"
                              class="edit-service-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="company_id" value="{{ $company->id }}">
                            <div class="mb-4">
                                <input type="text" name="name" placeholder="Service Name" value="{{ $service->name }}"
                                       class="w-full px-4 py-2 border rounded">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="price" placeholder="Service Price"
                                       value="{{ $service->price }}" class="w-full px-4 py-2 border rounded">
                            </div>
                            <div class="mb-4">
                                <input type="text" name="length" placeholder="Service Length"
                                       value="{{ $service->length }}" class="w-full px-4 py-2 border rounded">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mr-4">Save
                                    Changes
                                </button>
                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded close-modal">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($loggedInUserId === $company->user_id)
        <div class="mt-5">
            <button id="addServiceButton" class="bg-green-500 text-white px-4 py-2 rounded">Add Service</button>
        </div>
        <div id="addServiceModal"
             class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
                <div class="p-4 text-center">
                    <h2 class="text-xl font-bold mb-4">Add New Service</h2>
                    <form
                        action="{{ route('services.store', ['city' => $company->city, 'name' => $company->name, 'id' => $company->id]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <div class="mb-4">
                            <input type="text" name="name" placeholder="Service Name"
                                   class="w-full px-4 py-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="price" placeholder="Service Price"
                                   class="w-full px-4 py-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="length" placeholder="Service Length (minutes)"
                                   class="w-full px-4 py-2 border rounded">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mr-4">Add Service
                            </button>
                            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded close-modal">Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</main>
@include('partials.footer')

<div id="infoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
        <div class="p-4 text-center">
            <h2 class="text-xl font-bold mb-4">Info</h2>
            <p class="mb-4">Opening Hours</p>
            <div class="flex flex-col items-center">
                <span class="font-bold mb-2">Monday:</span>
                <span class="font-bold mb-2">Tuesday:</span>
                <span class="font-bold mb-2">Wednesday:</span>
                <span class="font-bold mb-2">Thursday:</span>
                <span class="font-bold mb-2">Friday:</span>
                <span class="font-bold mb-2">Saturday:</span>
                <span class="font-bold mb-2">Sunday:</span>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#infoButton').on('click', function () {
            $('#infoModal').removeClass('hidden');
        });

        $(window).on('click', function (event) {
            if ($(event.target).is('#infoModal')) {
                $('#infoModal').addClass('hidden');
            }
        });

        $('.service-div').on('click', function () {
            var modalId = $(this).data('modal-id');
            $('#' + modalId).removeClass('hidden');
        });

        $('.edit-service').on('click', function () {
            var modalId = $(this).data('modal-id');
            $('#' + modalId).removeClass('hidden');
        });

        $('.close-modal').on('click', function () {
            $(this).closest('.service-modal, #infoModal, #addServiceModal').addClass('hidden');
        });

        $('#addServiceButton').on('click', function () {
            $('#addServiceModal').removeClass('hidden');
        });

        $('.edit-service-form').on('submit', function (event) {
            event.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function (response) {
                    location.reload();
                },
                error: function (response) {
                    alert('There was an error updating the service.');
                }
            });
        });
    });
</script>
</body>
</html>
