@extends ('layout')

@section ('content')
    {{-- Link to intern's profile--}}
    <h3>Visite de stage n°{{$internship->id}} de <a href="#">{{$internship->lastname}}, {{$internship->firstname}}</a></h3>
    <br>
    <table class="table table-bordered col-md-10">
        <tr>
            <th class="col-md-1">Prénom de l'élève</th>
            <th class="col-md-1">Nom de l'élève</th>
            <th class="col-md-1">Entreprise</th>
            <th class="col-md-1">Date de la visite</th>
            <th class="col-md-1">Heure de la visite</th>
            <th class="col-md-1">Date de début de stage</th>
            <th class="col-md-1">Date de fin de stage</th>
            <th class="col-md-1">email</th>
        </tr>
        <tr class="text-left">
            <td class="col-md-1">{!! $internship->firstname !!}</td>
            <td class="col-md-1">{!! $internship->lastname !!}</td>
            <td class="col-md-1">{!! $internship->companyName !!}</td>
            <td class="col-md-1">
                <div id="vdate">
                    {{ (new DateTime($internship->moment))->format('d.m.Y') }}
                </div>
                <div id="dateedit" class="hidden">
                    <?php
                        $today = date('m/d/Y');
                    ?>
                    <input type="date" name="updtext" max="2018-06-25" min="" value="{{ (new DateTime($internship->moment))->format('Y-m-d') }}">
                </div>
            </td>
            <td class="col-md-1">
                <div id="vhour">
                    {{ (new DateTime($internship->moment))->format('H:i:s') }}
                </div>
                <div id="houredit" class="hidden">
                    <input type="time" name="updtext" value="{{ (new DateTime($internship->moment))->format('H:i') }}">
                </div>
            </td>
            <td class="col-md-1">{{ (new DateTime($internship->beginDate))->format('d.m.Y') }}</td>
            <td class="col-md-1">{{ (new DateTime($internship->endDate))->format('d.m.Y') }}</td>
            <td class="col-md-1">
                @if($internship->mailstate == 1)
                    <span class="ok glyphicon glyphicon-ok" style="color:green"></span>&nbsp;envoyé
                @else
                    <span class="remove glyphicon glyphicon-remove" style="color:red"></span>&nbsp;pas encore envoyé
                @endif
            </td>
        </tr>
        <tr>
            <th colspan="7" class="text-right">Etat de la visite</th>
            <td>{{ $internship->stateName }}</td>
        </tr>
    </table>
        @if($internship->visitsstates_id <= 2 || $internship->visitsstates_id == 4)
            <button id="edit" class="btn btn-info">Editer</button>
            <button id="up" class="btn btn-info hidden" type="submit">Enregistrer</button>
            <a id="del" class="hidden" href="/visits/{{ $internship->id }}/delete">
                <button class="btn btn-danger">Supprimer</button>
            </a>
            <a id="done" class="hidden" href="/visits/{{ $internship->id }}/state/"><button class="btn btn-success">Effectuée</button></a>
            <a id="cancel" class="btn btn-info hidden">Annuler</a>
            <button class="btn btn-success" onclick="mailto()">Envoyer un e-mail</button>
        @else
            <span class="remove glyphicon glyphicon-remove" style="color:red"></span>&nbsp;La visite a été {!! strtolower($internship->stateName) !!} et ne peut plus être modifiée
            <br>
        @endif

        {{-- Link to evaluation--}}
        @switch(\App\Http\Controllers\EvalController::getEvalState($internship->id))
            @case(1)
            <a href="/evalgrid/neweval/{{ $internship->id }}">
                <button class="btn btn-primary">Démarrer l'évaluation</button>
            </a>
            @break
            @case(2)
            <a href="/evalgrid/grid/edit/{{ $internship->id }}">
                <button class="btn btn-warning">Reprendre l'évaluation</button>
            </a>
            @break
            @case(3)
            <a href="/evalgrid/grid/readonly/{{ $internship->id }}">
                <button class="btn btn-secondary">Afficher l'évaluation</button>
            </a>
            @break
        @endswitch

    <br><br>

    {{-- Responsible table info --}}
    <table class="table table-bordered col-md-12">
        <tr>
            <th class="col-md-6">email du responsable</th>
            <th class="col-md-6">numéro de téléphone</th>
        </tr>
        <tr class="clickable-row text-left">
            <td class="col-md-6"><span class="mailstate">{{$contact->value}}</span></td>
            <!--<td class="col-md-6"><span class="mailstate">{{$contact->value}}</span></td>-->
        </tr>
    </table>
    <form method="post" action="/remarks/add" class="col-md-12 text-left">
        {{ csrf_field() }}
        <fieldset>
            <legend>Ajouter une remarque</legend>
            <textarea type="text" name="newremtext"></textarea>
            <input type="submit" value="Ok"/>
        </fieldset>
    </form>
    <br>
    <h3>Historique</h3>
    <table class="table table-striped text-left">
        <thead class="thead-inverse">
        <tr>
            <th>Type</th>
            <th>Description</th>
            <th>Date de modification</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($log as $logs)
            <tr>
                <td class="col-md-2">{{$logs->typeActivityDescription}}</td>
                <td class="col-md-8">{{$logs->activityDescription}}</td>
                <td class="col-md-2">{{ (new DateTime($logs->entryDate))->format('d M Y')}} à {{ (new DateTime($logs->entryDate))->format('H:i:s' ) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <script>
        //Fonction that open mail app and redirect the user to the main view
        function mailto()
        {
            //var email = {{$contact->value}};
            //var mailto_link = 'mailto:' + email;
            var url = '/visits/'+{{$internship->internships_id}}+'/mail';

            location.href = "mailto:jeanyvesle@hotmail.com?subject=Hello world&body=Line one%0DLine two";
            window.setTimeout(function(){ location.href = url },  1000);
        }
    </script>
@stop
@section ('page_specific_js')
    <script src="/js/remark.js"></script>
    <script src="/js/visit.js"></script>
@stop
