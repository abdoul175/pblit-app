@extends('partials.header')

<div class="container d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row">
        <div class="col-md-12 p-4">
            <div>
                <h3 class="text-center p-4">
                    Exporter votre fichier ici!
                </h3>
            </div>
            <div class="card">
                <div>
                    <h3 class="text-center p-4">
                        Saisissez le nom du fichier
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{route('export')}}" method="post">
                        @csrf
                        @if (session('error'))
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-danger text-center">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="mb-2">Nom du fichier</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                            <button type="reset" class="btn btn-danger">Annuler</button>
                            <a href="/" class="btn btn-secondary">Importer les données</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('partials.footer')