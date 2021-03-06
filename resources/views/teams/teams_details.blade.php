@extends("layouts.default")

@section("content")
    <div class="container">
        <div id="heading" style="text-align: center">
            <table width="100%">
                <tr>
                    <td><img src="{{$team->logo_path}}"></td>
                </tr>
                <tr>
                    <td style="vertical-align: top"><h3>{{$team->name}}</h3></td>
                </tr>
            </table>
        </div>

        {{-- Nav tabs  --}}
        <ul class="nav nav-tabs" id="nav_tabs" role="tablist">
            @if($last_fixtures->count() > 0)
                <li class="nav-item">
                    <a class="nav-link active" id="last_fixtures-tab" data-toggle="tab" href="#last_fixtures" role="tab" aria-controls="last_fixtures" aria-selected="true">@choice('application.last fixtures', $last_fixtures->count())</a>
                </li>
            @endif
            @if($upcoming_fixtures->count() > 0)
                <li class="nav-item">
                    <a class="nav-link" id="upcoming_fixtures-tab" data-toggle="tab" href="#upcoming_fixtures" role="tab" aria-controls="upcoming_fixtures" aria-selected="false">@choice('application.upcoming fixtures', $upcoming_fixtures->count())</a>
                </li>
            @endif
        </ul>

        {{-- Tab panes  --}}
        <div class="tab-content" id="tab_content">
            <div class="tab-pane fade show active" id="last_fixtures" role="tabpanel" aria-labelledby="last_fixtures-tab">
                @if($last_fixtures->count() > 0)
                    @php $last_league_id = 0; @endphp
                    @foreach($last_fixtures as $last_fixture)
                        @php
                            $league = $last_fixture->league->data;
                            $homeTeam = $last_fixture->localTeam->data;
                            $awayTeam = $last_fixture->visitorTeam->data;
                            if($last_fixture->scores->localteam_score > $last_fixture->scores->visitorteam_score && in_array($last_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = $homeTeam->name;
                            } elseif ($last_fixture->scores->localteam_score == $last_fixture->scores->visitorteam_score && in_array($last_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = "draw";
                            } elseif ($last_fixture->scores->localteam_score < $last_fixture->scores->visitorteam_score && in_array($last_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = $awayTeam->name;
                            } else {
                                $winningTeam = "TBD";
                            }
                        @endphp
                        @if($last_fixture->league_id == $last_league_id)
                            <tr>
                                {{-- show winning team in green, losing team in red, if draw, show both in orange --}}
                                @switch($winningTeam)
                                    @case($homeTeam->name)
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:green">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:red">{{$awayTeam->name}}</a></td>
                                        @break
                                    @case($awayTeam->name)
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:red">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:green">{{$awayTeam->name}}</a></td>
                                        @break
                                    @case("draw")
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:orange">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:orange">{{$awayTeam->name}}</a></td>
                                        @break
                                    @default
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}">{{$awayTeam->name}}</a></td>
                                        @break
                                @endswitch

                                {{-- show score, if FT_PEN -> show penalty score, if AET -> show (ET) --}}
                                @switch($last_fixture->time->status)
                                    @case("FT_PEN")
                                        <td scope="row">{{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}} ({{$last_fixture->scores->localteam_pen_score}} - {{$last_fixture->scores->visitorteam_pen_score}})</td>
                                        @break
                                    @case("AET")
                                        <td scope="row">{{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}} (ET)</td>
                                        @break
                                    @default
                                        <td scope="row">{{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}}</td>
                                        @break
                                @endswitch
    
                                <td scope="row">{{date($date_format . " H:i", strtotime($last_fixture->time->starting_at->date_time))}}
                                    @if($last_fixture->time->status == "LIVE")
                                        <span style="color:#FF0000">LIVE</span>
                                    @endif
                                </td>
                                <td scope="row"><a href= {{route("fixturesDetails", ["id" => $last_fixture->id])}}><i class="fa fa-info-circle"></i></a></td>
                            </tr>
                        @else
                            <table class="table table-striped table-light table-sm" style="width:100%">
                                <caption>
                                    <a href=" {{route("leaguesDetails", ["id" => $league->id])}}" style="font-weight: bold">{{$league->name}}</a>
                                </caption>
                                <thead>
                                <tr>
                                    <th scope="col" width="32%"></th>
                                    <th scope="col" width="32%"></th>
                                    <th scope="col" width="11%"></th>
                                    <th scope="col" width="17%"></th>
                                    <th scope="col" width="5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    {{-- show winning team in green, losing team in red, if draw, show both in orange --}}
                                    @switch($winningTeam)
                                        @case($homeTeam->name)
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:green">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:red">{{$awayTeam->name}}</a></td>
                                            @break
                                        @case($awayTeam->name)
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:red">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:green">{{$awayTeam->name}}</a></td>
                                            @break
                                        @case("draw")
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:orange">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:orange">{{$awayTeam->name}}</a></td>
                                            @break
                                        @default
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}">{{$awayTeam->name}}</a></td>
                                            @break
                                    @endswitch

                                    {{-- show score, if FT_PEN -> show penalty score, if AET -> show (ET) --}}
                                    @switch($last_fixture->time->status)
                                        @case("FT_PEN")
                                            <td scope="row">{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}} ({{$last_fixture->scores->localteam_pen_score}} - {{$last_fixture->scores->visitorteam_pen_score}}) </td>
                                            @break
                                        @case("AET")
                                            <td scope="row">{{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}} (ET)</td>
                                            @break
                                        @default
                                            <td scope="row">{{$last_fixture->scores->localteam_score}} - {{$last_fixture->scores->visitorteam_score}}</td>
                                            @break
                                    @endswitch

                                    {{-- show date_time, if LIVE -> show LIVE after date_time --}}
                                    <td scope="row">{{date($date_format . " H:i", strtotime($last_fixture->time->starting_at->date_time))}}
                                        @if($last_fixture->time->status == "LIVE")
                                            <span style="color:#FF0000">LIVE</span>
                                        @endif
                                    </td>
                                    {{-- show button to view fixtures-details --}}
                                    <td scope="row"><a href="{{route("fixturesDetails", ["id" => $last_fixture->id])}}"><i class="fa fa-info-circle"></i></a></td>
                                </tr>
                        @endif
                        @php $last_league_id = $last_fixture->league_id; @endphp
                    @endforeach
                    </tbody>
                    </table>
                @endif
            </div>

            <div class="tab-pane fade" id="upcoming_fixtures" role="tabpanel" aria-labelledby="upcoming_fixtures-tab">
                @if($upcoming_fixtures->count() > 0)
                    @php $last_league_id = 0; @endphp
                    @foreach($upcoming_fixtures as $upcoming_fixture)
                        @php
                            $league = $upcoming_fixture->league->data;
                            $homeTeam = $upcoming_fixture->localTeam->data;
                            $awayTeam = $upcoming_fixture->visitorTeam->data;
                            if($upcoming_fixture->scores->localteam_score > $upcoming_fixture->scores->visitorteam_score && in_array($upcoming_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = $homeTeam->name;
                            } elseif ($upcoming_fixture->scores->localteam_score == $upcoming_fixture->scores->visitorteam_score && in_array($upcoming_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = "draw";
                            } elseif ($upcoming_fixture->scores->localteam_score < $upcoming_fixture->scores->visitorteam_score && in_array($upcoming_fixture->time->status,  array("FT", "AET", "FT_PEN"))) {
                                $winningTeam = $awayTeam->name;
                            } else {
                                $winningTeam = "TBD";
                            }
                        @endphp
                        @if($upcoming_fixture->league_id == $last_league_id)
                            <tr>
                                {{-- show winning team in green, losing team in red, if draw, show both in orange --}}
                                @switch($winningTeam)
                                    @case($homeTeam->name)
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:green">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:red">{{$awayTeam->name}}</a></td>
                                        @break
                                    @case($awayTeam->name)
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:red">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:green">{{$awayTeam->name}}</a></td>
                                        @break
                                    @case("draw")
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:orange">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:orange">{{$awayTeam->name}}</a></td>
                                        @break
                                    @default
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}">{{$homeTeam->name}}</a></td>
                                        <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}">{{$awayTeam->name}}</a></td>
                                        @break
                                @endswitch

                                {{-- show score, if FT_PEN -> show penalty score, if AET -> show (ET) --}}
                                @switch($upcoming_fixture->time->status)
                                    @case("FT_PEN")
                                        <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}} ({{$upcoming_fixture->scores->localteam_pen_score}} - {{$upcoming_fixture->scores->visitorteam_pen_score}})</td>
                                        @break
                                    @case("AET")
                                        <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}} (ET)</td>
                                        @break
                                    @default
                                        <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}}</td>
                                    @break
                                @endswitch
    
                                <td scope="row">{{date($date_format . " H:i", strtotime($upcoming_fixture->time->starting_at->date_time))}}
                                    @if($upcoming_fixture->time->status == "LIVE")
                                        <span style="color:#FF0000">LIVE</span>
                                @endif
                                <td scope="row"><a href= {{route("fixturesDetails", ["id" => $upcoming_fixture->id])}}><i class="fa fa-info-circle"></i></a></td>
                            </tr>
                        @else
                            <table class="table table-striped table-light table-sm" style="width:100%">
                                <caption>
                                    <a href=" {{route("leaguesDetails", ["id" => $league->id])}}" style="font-weight: bold">{{$league->name}}</a>
                                </caption>
                                <thead>
                                <tr>
                                    <th scope="col" width="32%"></th>
                                    <th scope="col" width="32%"></th>
                                    <th scope="col" width="11%"></th>
                                    <th scope="col" width="17%"></th>
                                    <th scope="col" width="5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    {{-- show winning team in green, losing team in red, if draw, show both in orange --}}
                                    @switch($winningTeam)
                                        @case($homeTeam->name)
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:green">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:red">{{$awayTeam->name}}</a></td>
                                            @break
                                        @case($awayTeam->name)
                                            <td scope="row">
                                            <a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:red">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:green">{{$awayTeam->name}}</a></td>
                                            @break
                                        @case("draw")
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}" style="color:orange">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}" style="color:orange">{{$awayTeam->name}}</a></td>
                                            @break
                                        @default
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $homeTeam->id])}}">{{$homeTeam->name}}</a></td>
                                            <td scope="row"><a href="{{route("teamsDetails", ["id" => $awayTeam->id])}}">{{$awayTeam->name}}</a></td>
                                            @break
                                    @endswitch

                                {{-- show score, if FT_PEN -> show penalty score, if AET -> show (ET) --}}
                                    @switch($upcoming_fixture->time->status)
                                        @case("FT_PEN")
                                            <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}} ({{$upcoming_fixture->scores->localteam_pen_score}} - {{$upcoming_fixture->scores->visitorteam_pen_score}})</td>
                                            @break
                                        @case("AET")
                                            <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}} (ET)</td>
                                            @break
                                        @default
                                            <td scope="row">{{$upcoming_fixture->scores->localteam_score}} - {{$upcoming_fixture->scores->visitorteam_score}}</td>
                                            @break
                                    @endswitch
    
                                    <td scope="row">{{date($date_format . " H:i", strtotime($upcoming_fixture->time->starting_at->date_time))}}
                                        @if($upcoming_fixture->time->status == "LIVE")
                                            <span style="color:#FF0000">LIVE</span>
                                    @endif
                                    <td scope="row"><a href="{{route("fixturesDetails", ["id" => $upcoming_fixture->id])}}"><i class="fa fa-info-circle"></i></a></td>
                                </tr>
                        @endif
                        @php $last_league_id = $upcoming_fixture->league_id; @endphp
                    @endforeach
                    </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection