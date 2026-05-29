{{-- TOAST --}}
<div x-data="{ show: false }" x-init="@if (session('success')) show=true;setTimeout(()=>show=false,3500); @endif">
    <div x-show="show" x-transition
        style="position:fixed;top:20px;right:20px;z-index:999;background:#fff;border:1px solid rgba(0,0,0,.11);border-radius:16px;padding:13px 18px;display:flex;align-items:center;gap:11px;box-shadow:0 4px 28px rgba(0,0,0,.08);font-family:'Barlow',sans-serif;">
        <div
            style="width:30px;height:30px;background:rgba(184,147,42,.10);color:#B8932A;border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-check" style="font-size:14px;"></i>
        </div>
        <div>
            <div style="font-weight:700;font-size:13px;color:#1A1916;">Action réussie</div>
            <div style="font-size:11px;color:#A8A49E;">{{ session('success') }}</div>
        </div>
    </div>
</div>

<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Barlow:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --gold: #B8932A;
            --gold-l: #D4A843;
            --gold-dim: rgba(184, 147, 42, .10);
            --gold-bd: rgba(184, 147, 42, .22);
            --gold-sh: rgba(184, 147, 42, .16);
            --bg: #F7F6F3;
            --sur: #FFFFFF;
            --sur2: #FAFAF8;
            --sur3: #F2F1EE;
            --bd: rgba(0, 0, 0, .07);
            --bd2: rgba(0, 0, 0, .11);
            --text: #1A1916;
            --t2: #6B6860;
            --t3: #A8A49E;
            --sb: #1A1916;
            --sb-bd: rgba(255, 255, 255, .07);
            --sb-t: rgba(255, 255, 255, .50);
            --pos: #2D6A4F;
            --pos-bg: rgba(45, 106, 79, .08);
            --pos-bd: rgba(45, 106, 79, .18);
            --neg: #7A2020;
            --neg-bg: rgba(122, 32, 32, .08);
            --neg-bd: rgba(122, 32, 32, .18);
            --r24: 24px;
            --r18: 18px;
            --r12: 12px;
            --r8: 8px;
            --rfull: 9999px;
            --sh: 0 2px 16px rgba(0, 0, 0, .06);
            --shm: 0 4px 28px rgba(0, 0, 0, .08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Barlow', sans-serif;
            color: var(--text);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        .ff {
            display: flex;
            min-height: 100vh;
        }

        .sb {
            width: 224px;
            flex-shrink: 0;
            background: var(--sb);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sb-logo {
            padding: 26px 20px 22px;
            border-bottom: 1px solid var(--sb-bd);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sb-logo-t {
            font-family: 'Instrument Serif', serif;
            font-size: 17px;
            color: #fff;
        }

        .sb-logo-t span {
            color: var(--gold-l);
        }

        .sb-sec {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .22);
            padding: 18px 20px 6px;
        }

        .sb-nav {
            flex: 1;
            padding-top: 8px;
        }

        .sb-a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--sb-t);
            text-decoration: none;
            transition: all .15s;
            border-left: 2px solid transparent;
        }

        .sb-a:hover {
            color: rgba(255, 255, 255, .80);
            background: rgba(255, 255, 255, .04);
        }

        .sb-a.on {
            color: var(--gold-l);
            background: rgba(212, 168, 67, .10);
            border-left-color: var(--gold-l);
            font-weight: 600;
        }

        .sb-a svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            opacity: .7;
        }

        .sb-a.on svg {
            opacity: 1;
        }

        .sb-foot {
            padding: 16px 18px;
            border-top: 1px solid var(--sb-bd);
        }

        .upill {
            display: flex;
            align-items: center;
            gap: 9px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid var(--sb-bd);
            border-radius: var(--rfull);
            padding: 7px 12px 7px 7px;
        }

        .av {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-l));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
            color: #1A1916;
            flex-shrink: 0;
        }

        .sb-nm {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, .75);
        }

        .mn {
            flex: 1;
            padding: 34px 38px;
            overflow-y: auto;
            min-width: 0;
        }

        .tp {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .tp-ey {
            font-size: 11px;
            font-weight: 600;
            color: var(--t3);
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .tp-h {
            font-family: 'Instrument Serif', serif;
            font-size: 34px;
            color: var(--text);
            letter-spacing: -.6px;
            line-height: 1;
        }

        .tp-r {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .icb {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--sur);
            border: 1px solid var(--bd2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t2);
            transition: all .18s;
            box-shadow: var(--sh);
        }

        .icb:hover {
            border-color: var(--gold-bd);
            color: var(--gold);
        }

        .icb svg {
            width: 15px;
            height: 15px;
        }

        .tp-av {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-l));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #1A1916;
        }

        .hero {
            background: var(--sb);
            border: 1px solid rgba(184, 147, 42, .20);
            border-radius: var(--r24);
            padding: 26px 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .12);
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(184, 147, 42, .12) 0%, transparent 65%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(212, 168, 67, .4), transparent);
        }

        .hero-l h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 28px;
            color: #fff;
            margin-bottom: 4px;
        }

        .hero-l p {
            font-size: 12px;
            color: rgba(255, 255, 255, .45);
        }

        .hero-r {
            text-align: right;
            position: relative;
            z-index: 1;
        }

        .hero-lbl {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .38);
            margin-bottom: 6px;
        }

        .hero-amt {
            font-family: 'Instrument Serif', serif;
            font-size: 36px;
            color: #fff;
            letter-spacing: -1.5px;
            line-height: 1;
        }

        .hero-amt small {
            font-size: 14px;
            font-family: 'Barlow', sans-serif;
            font-weight: 300;
            color: rgba(255, 255, 255, .38);
            margin-left: 4px;
        }

        .hero-ct {
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
            margin-top: 4px;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: #fff;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: var(--rfull);
            border: none;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 14px var(--gold-sh);
        }

        .btn-add:hover {
            background: var(--gold-l);
            transform: translateY(-1px);
        }

        .btn-add svg {
            width: 15px;
            height: 15px;
        }

        .tr-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .tbl-wrap {
            background: var(--sur);
            border: 1px solid var(--bd);
            border-radius: var(--r24);
            overflow: hidden;
            box-shadow: var(--sh);
        }

        .tbl-hd {
            padding: 16px 20px 14px;
            border-bottom: 1px solid var(--bd);
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .tbl-hd h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .tbl-hd span {
            font-size: 12px;
            color: var(--t3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 11px 18px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: var(--t3);
            background: var(--sur2);
            border-bottom: 1px solid var(--bd);
        }

        th:last-child {
            text-align: center;
        }

        td {
            padding: 13px 18px;
            font-size: 13px;
            color: var(--text);
            border-bottom: 1px solid var(--bd);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background .13s;
        }

        tbody tr:hover {
            background: var(--sur3);
        }

        td:last-child {
            text-align: center;
        }

        .pill-neg {
            display: inline-flex;
            background: var(--neg-bg);
            color: var(--neg);
            font-size: 12px;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: var(--rfull);
            border: 1px solid var(--neg-bd);
        }

        .pill-cat {
            display: inline-flex;
            background: var(--sur3);
            color: var(--t2);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: var(--rfull);
            border: 1px solid var(--bd);
        }

        .td-dt {
            color: var(--t3);
            font-size: 12px;
        }

        .td-nm {
            font-weight: 600;
        }

        .acts {
            display: flex;
            gap: 7px;
            justify-content: center;
        }

        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: var(--r8);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--bd2);
            cursor: pointer;
            transition: all .15s;
            background: var(--sur);
            color: var(--t2);
            font-size: 13px;
        }

        .act-btn:hover {
            background: var(--sur3);
        }

        .act-btn.e:hover {
            border-color: var(--gold-bd);
            color: var(--gold);
        }

        .act-btn.d:hover {
            border-color: var(--neg-bd);
            color: var(--neg);
        }

        .emp {
            text-align: center;
            padding: 48px 20px;
            color: var(--t3);
            font-size: 13px;
        }

        .emp svg {
            width: 30px;
            height: 30px;
            margin: 0 auto 10px;
            display: block;
            opacity: .3;
        }

        .m-ov {
            position: fixed;
            inset: 0;
            background: rgba(20, 18, 14, .55);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 200;
            padding: 20px;
        }

        .m-ov.open {
            display: flex;
        }

        .modal {
            background: var(--sur);
            border: 1px solid var(--bd2);
            border-radius: 24px;
            padding: 28px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 32px 80px rgba(0, 0, 0, .14);
            position: relative;
        }

        .modal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-bd), transparent);
            border-radius: 24px 24px 0 0;
        }

        .m-hd {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .m-ttl {
            font-family: 'Instrument Serif', serif;
            font-size: 20px;
            color: var(--text);
        }

        .m-sub {
            font-size: 12px;
            color: var(--t3);
            margin-top: 2px;
        }

        .m-cls {
            width: 28px;
            height: 28px;
            border-radius: var(--r8);
            border: 1px solid var(--bd2);
            background: var(--sur2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--t3);
            flex-shrink: 0;
        }

        .m-cls:hover {
            color: var(--text);
        }

        .m-cls svg {
            width: 14px;
            height: 14px;
        }

        .f-lbl {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: var(--t3);
            margin-bottom: 6px;
        }

        .f-inp {
            width: 100%;
            background: var(--sur2);
            border: 1.5px solid var(--bd2);
            border-radius: var(--r12);
            padding: 12px 14px;
            font-size: 14px;
            font-family: 'Barlow', sans-serif;
            font-weight: 500;
            color: var(--text);
            outline: none;
            transition: border-color .18s;
            margin-bottom: 15px;
        }

        .f-inp:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-dim);
        }

        .f-inp::placeholder {
            color: var(--t3);
            font-weight: 400;
        }

        .f-inp.big {
            font-family: 'Instrument Serif', serif;
            font-size: 24px;
            padding: 14px;
        }

        .f-sel {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%23A8A49E' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 15px;
        }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 6px;
        }

        .btn-ghost {
            flex: 1;
            background: var(--sur2);
            border: 1px solid var(--bd2);
            color: var(--t2);
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 13px;
            border-radius: var(--r12);
            cursor: pointer;
        }

        .btn-ghost:hover {
            background: var(--sur3);
            color: var(--text);
        }

        .btn-primary {
            flex: 1;
            background: var(--gold);
            color: #fff;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            font-weight: 700;
            padding: 13px;
            border-radius: var(--r12);
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--gold-l);
        }

        @media(max-width:720px) {
            .sb {
                display: none;
            }

            .mn {
                padding: 18px;
            }

            .hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-r {
                text-align: left;
            }
        }
    </style>

    <div x-data="{ openEdit: false, editId: null, montant: '', categorie: '', titre: '' }" class="ff">

        {{-- SIDEBAR --}}
        <aside class="sb">
            <a href="/dashboard" class="sb-logo">
                <svg viewBox="0 0 32 32" fill="none" style="width:32px;height:32px;flex-shrink:0">
                    <rect width="32" height="32" rx="8" fill="#D4A843" fill-opacity=".12" />
                    <circle cx="16" cy="16" r="9.5" stroke="#D4A843" stroke-width="1.4" fill="none" />
                    <circle cx="16" cy="16" r="6" stroke="#D4A843" stroke-width=".8" fill="none"
                        opacity=".45" /><text x="16" y="20.5" text-anchor="middle" font-family="Georgia,serif"
                        font-size="9.5" font-weight="bold" fill="#D4A843">₣</text>
                </svg>
                <div class="sb-logo-t">Finance<span>Flow</span></div>
            </a>
            <nav class="sb-nav">
                <div class="sb-sec">Principal</div>
                <a href="/dashboard" class="sb-a"><i class="fas fa-th"></i>Tableau de bord</a>
                <div class="sb-sec">Finances</div>
                <a href="/revenus" class="sb-a"><i class="fas fa-arrow-trend-up"></i>Revenus</a>
                <a href="/depenses" class="sb-a on"><i class="fas fa-wallet"></i>Dépenses</a>
                <a href="/budgets" class="sb-a"><i class="fas fa-chart-pie"></i>Budget</a>
                <a href="/rapports" class="sb-a"><i class="fas fa-chart-bar"></i>Rapports</a>
            </nav>
            <div class="sb-foot">
                <div class="upill">
                    <div class="av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><span
                        class="sb-nm">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </aside>

        <main class="mn">
            <div class="tp">
                <div>
                    <p class="tp-ey">Gestion financière</p>
                    <h1 class="tp-h">Mes Dépenses</h1>
                </div>
                <div class="tp-r">
                    <div class="icb"><i class="fas fa-magnifying-glass"></i></div>
                    <div class="tp-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </div>

            <div class="hero">
                <div class="hero-l">
                    <h2>Mes Dépenses</h2>
                    <p>Gardez le contrôle sur vos sorties d'argent</p>
                </div>
                <div class="hero-r">
                    <div class="hero-lbl">Dépensé ce mois</div>
                    <div class="hero-amt">{{ number_format($depensesMois ?? 0, 0, ',', ' ') }}<small>FCFA</small></div>
                    <div class="hero-ct">{{ $depenses->count() }} transaction(s)</div>
                </div>
            </div>

            <div x-data="{ open: false }">
                <div class="tr-row">
                    <button @click="open=true" class="btn-add">
                        <i class="fas fa-plus"></i>Ajouter une dépense</button>
                </div>

                <div x-show="open" @click.self="open=false" class="m-ov" :class="{ open }"
                    style="display:none;">
                    <div class="modal">
                        <div class="m-hd">
                            <div>
                                <div class="m-ttl">Nouvelle dépense</div>
                                <div class="m-sub">Remplissez les informations</div>
                            </div>
                            <div class="m-cls" @click="open=false"><i class="fas fa-times"></i></div>
                        </div>
                        <form action="{{ route('depenses.store') }}" method="POST">
                            @csrf
                            <label class="f-lbl">Montant (FCFA)</label><input type="number" name="montant"
                                placeholder="0" class="f-inp big">
                            <label class="f-lbl">Libellé</label><input type="text" name="description"
                                placeholder="Ex : Restaurant…" class="f-inp">
                            <label class="f-lbl">Catégorie</label>
                            <select name="category_id" required class="f-inp f-sel">
                                <option value="">Choisir une catégorie</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="btn-row"><button type="button" @click="open=false"
                                    class="btn-ghost">Annuler</button><button type="submit"
                                    class="btn-primary">Valider</button></div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tbl-wrap">
                <div class="tbl-hd">
                    <h3>Historique des dépenses</h3><span>{{ $depenses->count() }} entrée(s)</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depenses as $d)
                            <tr>
                                <td class="td-nm">{{ $d->description }}</td>
                                <td><span class="pill-neg">-{{ number_format($d->amount, 0, ',', ' ') }} FCFA</span>
                                </td>
                                <td><span class="pill-cat">{{ $d->category->name ?? '—' }}</span></td>
                                <td class="td-dt">{{ $d->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="acts">
                                        <button
                                            @click="editId={{ $d->id }};montant='{{ $d->amount }}';categorie='{{ $d->category->id ?? '' }}';titre='{{ addslashes($d->description) }}';openEdit=true"
                                            class="act-btn e">✏️</button>
                                        <form action="{{ route('depenses.destroy', $d) }}" method="POST"
                                            style="display:inline;">@csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer ?')"
                                                class="act-btn d">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="emp"><i class="fas fa-info-circle"></i>Aucune dépense
                                    enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MODAL EDIT --}}
            <div x-show="openEdit" x-transition.opacity @click.self="openEdit=false" class="m-ov"
                :class="{ open: openEdit }" style="display:none;">
                <div class="modal">
                    <div class="m-hd">
                        <div>
                            <div class="m-ttl">Modifier la dépense</div>
                            <div class="m-sub">Mettez à jour les informations</div>
                        </div>
                        <div class="m-cls" @click="openEdit=false"><i class="fas fa-times"></i></div>
                    </div>
                    <form :action="'/depenses/' + editId" method="POST">
                        @csrf @method('PUT')
                        <label class="f-lbl">Montant (FCFA)</label><input type="number" name="montant"
                            x-model="montant" class="f-inp big">
                        <label class="f-lbl">Libellé</label><input type="text" name="description"
                            x-model="titre" class="f-inp">
                        <label class="f-lbl">Catégorie</label>
                        <select name="category_id" x-model="categorie" class="f-inp f-sel">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="btn-row"><button type="button" @click="openEdit=false"
                                class="btn-ghost">Annuler</button><button type="submit"
                                class="btn-primary">Enregistrer</button></div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
