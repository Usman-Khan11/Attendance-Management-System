@extends('user.layouts.auth')

@push('style')
    <style>
        body {
            scroll-behavior: smooth;
        }

        * {
            scrollbar-color: #555 #111;
        }

        .trello {
            width: 100%;
            height: 100vh;
            background-image: url(/assets/img/trello_bg.webp);
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            overflow-y: hidden;
        }

        .items {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 15px 0px 0 0px;
            width: max-content;
            overflow: hidden;
        }

        .item {
            width: 280px;
            border-radius: 12px;
            scroll-margin: 8px;
            overflow: hidden;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
            background: #101204;
        }

        .item .header {
            padding: 12px;
            /* border-bottom: 1px solid #ddd; */
        }

        .item .header h5 {
            margin: 0;
            font-size: 15px;
            color: #CECFD2;
            padding: 0 5px;
        }

        .item .body {
            min-height: 10px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            scrollbar-width: thin;
            color: #CECFD2;
        }

        .item .footer {
            padding: 10px;
            /* border-top: 1px solid #ddd; */
        }

        .item .footer .btn {
            box-shadow: none;
            width: 100%;
            justify-content: start;
            color: #A9ABAF;
        }

        .item .footer .btn:hover {
            background-color: #E3E4F21F;
        }

        .lists {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list {
            display: block;
            margin: 8px 10px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            background: #242528;
        }

        .list:hover {
            border: 2px solid #999;
        }

        .list p {
            margin: 0;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .add_list {
            margin-right: 20px;
        }

        .add_list .btn {
            box-shadow: none;
            width: 100%;
            background: #E3E4F24F;
            color: #f5f5f5;
        }

        .add_list .btn:hover {
            background-color: #E3E4F21F;
        }
    </style>
@endpush

@section('content')
    <section class="trello">
        <div class="w-100">
            <div class="card" style="background-color: transparent;">
                <div class="card-header" style="background-color: rgba(0,0,0,.4);">
                    <div class="row align-items-center g-3">
                        <div class="col-md-1 col-3">
                            <a href="{{ route('admin.board') }}" class="btn btn-light btn-sm">
                                <i class="fa fa-arrow-left me-2"></i>
                                Go Back
                            </a>
                        </div>
                        <div class="col-md-9 col-9">
                            <h4 class="fw-bold m-0 text-white">{{ $board->name }}</h4>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="add_list">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#listAddModal" class="btn">
                                    <i class="fa fa-plus me-2"></i>
                                    Add new List
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="items">
                        @foreach ($board->lists()->orderBy('position')->get() as $list)
                            <li class="item" data-id="{{ $list->id }}">
                                <div class="header">
                                    <h5>{{ $list->name }}</h5>
                                </div>

                                <div class="body">
                                    <ul class="lists">
                                        @foreach ($list->cards()->orderBy('position')->get() as $card)
                                            <li class="list">
                                                <p>{{ uniqid() }}</p>

                                                <button class="btn btn-label-danger btn-sm">
                                                    <i class="fa fa-clock me-2"></i>
                                                    Feb 27, 2026
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="footer">
                                    <button class="btn">
                                        <i class="fa fa-plus me-2"></i>
                                        Add a card
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="listAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.board.add_list', $board->id) }}" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add List</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter List Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        $(".items").each(function() {
            $(this).sortable({
                connectWith: ".items",
                delay: 50,
                revert: true,
                tolerance: "pointer",
                update: function(event, ui) {
                    let newIndex = ui.item.index();
                    let order = [];

                    $(this).children().each(function(index) {
                        order.push({
                            id: $(this).data('id'),
                            position: index
                        });
                    });

                    if (order.length) {
                        $.get(window.location.href, {
                            data: order,
                            type: 'update_list_position'
                        }, function(res) {})
                    }
                },
            }).disableSelection();
        })

        $(".lists").each(function() {
            $(this).sortable({
                connectWith: ".lists",
                delay: 50,
                revert: true,
                scroll: true,
                scrollSensitivity: 20,
                scrollSpeed: 40,
                tolerance: "pointer",
                receive: function(event, ui) {
                    console.log(event)
                    console.log(ui)
                }
            }).disableSelection();
        })

        $('#listAddModal form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: {
                    _token: CSRF_TOKEN,
                    name: $(form).find('input[name=name]').val()
                },
                beforeSend: function() {
                    $(form).find('.btn-primary').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success == 1) {
                        let data = response.data;

                        $('#listAddModal form').trigger('reset');
                        $('#listAddModal').modal('hide');

                        $('.items').append(`
                            <li class="item" data-id="${data.id}">
                                <div class="header">
                                    <h5>${data.name}</h5>
                                </div>
                                <div class="body"></div>
                                <div class="footer">
                                    <button class="btn">
                                        <i class="fa fa-plus me-2"></i>
                                        Add a card
                                    </button>
                                </div>
                            </li>
                        `);

                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else {
                        notify('error', xhr.responseJSON.message || 'Request failed');
                    }
                },
                complete: function() {
                    $(form).find('.btn-primary').prop('disabled', false);
                }
            });
        })
    </script>
@endpush
