@extends('layouts.app')

@section('title')
    MVO Intranet | Formations
@endsection

@section('content')

    <h1 class="text-center">Nos formations</h1>
    <div class="main__content container">

        <div id="accordion">

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                    <button class="btn collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Ajouter une formation
                    </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body" id="form__style">
                        <form action="{{ route('formation.storeFormation') }}" method="POST">
                            @csrf
        
                            <div class="form-floating mb-3">
                                <input type="text" id="name" class="form-control" name="wording" placeholder="Nom" value="{{ old('wording') }}" required>
                                <label for="name">Nom</label>

                                @error('wording')
                                    <div class="form-control alert-danger" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
        
                            <div class="vstack gap-2 col-md-5 mx-auto">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>

            <div class="card">
                <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        G??rer les sessions
                    </button>
                </h5>
                </div>
            
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <div>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                @if ($formations->isEmpty())
                            <div>
                            <div class="alert alert-dark" role="alert">
                                Aucune formation n'a ??t?? trouv??e
                            </div>
                        </div>
                    @else
                        <table class="table caption-top">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($formations as $key => $formation)
                                    @if ($formation->disable === NULL)
                                        <tr>
                                                <td class="align-middle"></td>
                                                <td class="align-middle"><?= $formation->wording ?></td>
                                                <td><a href="formations/<?= $formation->id ?>" class="btn btn-primary">Modifier</a></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary id" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="<?= $formation->id ?>">
                                                        Supprimer
                                                    </button>
                                                </td>
                                            </tr>
                                        @else 
                                        <tr class="disable">
                                            <td class="align-middle"></td>
                                            <td class="align-middle"><?= $formation->wording ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{ $formations->links() }}
                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade modalDelete" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Voulez-vous supprimer cette formation ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                        
                        <form class="delete_buttons_form" action="" method="post">
                            @csrf

                            @method('DELETE')

                            <button type="submit" class="btn btn-primary">Oui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        // Add
        const pcli = document.querySelector("#cli");
        const modalFormb = document.querySelector(".modalMember .modal-footer>button+form");
        
        pcli.addEventListener('click', () => {
                modalFormb.setAttribute("action", `http://localhost:8000/formation/aaa`);
        });

        // Delete 
        const butonsId = document.querySelectorAll("button[type=button].id");
        const modalForm = document.querySelector(".modalDelete .modal-footer>button+form");
        
        butonsId.forEach(butonId => {
            butonId.addEventListener('click', () => {
                modalForm.setAttribute("action", `http://localhost:8000/formation/${butonId.dataset.id}`);
            });
        });

    </script>
@endsection