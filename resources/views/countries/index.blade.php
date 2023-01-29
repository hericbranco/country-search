<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Countries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #e0e0e5;
        }

        .pagination .page-item a {
            color: #b1b1b5;
        }

        .slc-limit {
            width: 90px;
        }

        .flag {
            width: 150px;
        }

        .flagTh {
            width: 160px;
        }

        .table tr td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><i class="bi bi-flag-fill"></i>&nbsp;Countries</h1>
                <div class="card mb-3">
                    <div class="card-header">
                        Use the fields bellow to search
                    </div>
                    <div class="card-body">
                        @if (!empty($countries['errors']))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($countries['errors'] as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="container">
                            <form class="row g-3" method="GET" id="frmPrincipal">
                                <input type="hidden" name="limit" id="inpLimit" value="{{request('limit')}}">
                                <div class="col-auto">
                                    <label for="q" class="visually-hidden">Code/Country</label>
                                    <input type="text" class="form-control" id="q" name="q" placeholder="Code/Country" value="{{request('q')}}">
                                </div>
                                <div class="col-auto">
                                    <label for="region" class="visually-hidden">Region</label>
                                    <select class="form-select" name="region">
                                        <option value="">All</option>
                                        @foreach (['Africa', 'Asia', 'Europe', 'Oceania', 'North America', 'South America'] as $region)
                                        <option {{(request('region') == $region)?'selected':''}}>{{$region}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label for="with_flag" class="visually-hidden">With Flag?</label>
                                    <select class="form-select" name="with_flag">
                                        @foreach (['Without Flags', 'With Flags'] as $option)
                                        <option {{(request('with_flag') == $option)?'selected':''}}>{{$option}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-search"></i>&nbsp;Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover">
                            <tr>
                                @if (request('with_flag') == 'With Flags')
                                <th class="flagTh">Flag</th>
                                @endif
                                <th>Code</th>
                                <th>Country</th>
                                <th>Region</th>
                            </tr>
                            @forelse ($countries['data'] as $code => $country)
                            <tr>
                                @if (request('with_flag') == 'With Flags')
                                <td><img loading="lazy" class="flag" crossorigin="anonymous" src="{{config('custom.FlagsImgUrl')}}/{{$code}}" /></td>
                                @endif
                                <td>{{$code}}</td>
                                <td>{{$country->country}}</td>
                                <td>{{$country->region}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No results found</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                    @if (!empty($countries['data']))

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6 float-left">
                                    <select class="form-select slc-limit" name="limit" onchange="document.getElementById('inpLimit').value = this.value;document.getElementById('frmPrincipal').submit();">
                                        @foreach (['15', '30', '60', '90', '150'] as $limit)
                                        <option {{(request('limit') == $limit)?'selected':''}}>{{$limit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 float-right">{{ $countries['data']->links() }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>