@extends('partials.header')

<div class="container d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row">
        <div class="col-md-12 p-4">
            <div>
                <h3 class="text-center p-4">
                    Bienvenue!
                </h3>
            </div>
            <div class="card">
                <div class="p-5">
                    <h3 class="text-center p-4">
                        Sélectionner les fichiers (extension autorisée: .xlsx)
                    </h3>
                    @if (count($envoies) > 0 && count($retours) > 0)
                        <div class="alert alert-danger text-center">
                            <p><strong>Vous avez importer des données récemment! Cliquez sur le bouton "Exporter" pour l'exporter ou sur le bouton "Ignorer" pour ignorer ces données</strong></p>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            <p><strong>{{ session('success') }}</strong></p>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{route('import')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file" class="mb-2">Fichier source</label>
                                    <input type="file" class="form-control" id="file" name="file" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file_2" class="mb-2">Fichier banque</label>
                                    <input type="file" class="form-control" id="file_2" name="file_2" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center d-flex justify-content-space-between align-items-center">
                            @if (count($envoies) == 0 && count($retours) == 0)
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                                <button type="reset" class="btn btn-danger">Annuler</button>
                            </div>
                            @endif
                            @if (count($envoies) > 0 && count($retours) > 0)
                            <div class="w-100">
                                <a href="/export" class="btn btn-info">Exporter</a>
                                <a href="/delete-export-data" class="btn btn-outline-secondary">Ignorer</a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('partials.footer')