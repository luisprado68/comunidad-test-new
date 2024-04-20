@extends('layouts.main')

@section('content')
    <div class="container mt-5 ">
        <div class="row">
            @if (session()->has('user') && session('status') == 0)
                @include('link')
            @else
                <div class="d-flex justify-content-center">
                    <div class="mb-4 card bg-secondary profile">

                        <div class="p-0 m-0 card-body bg-secondary">
                            <div class="bg-black card ">
                                <h5 class="mt-2 text-center card-title text-light ">Edita tu Perfil</h5>
                            </div>
                            <form action="{{ route('edit-user') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="mt-3 mb-3 col-8">

                                        <input type="text" class="form-control" aria-label="Default" name="id"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->id }}"
                                            style="display: none">
                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white"
                                            for="channel">Canal</label>
                                        <input type="text" class="form-control" aria-label="Default" name="channel"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->channel }}">
                                        @error('channel')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.channel') }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white" for="name">Nombre
                                            Completo</label>
                                        <input type="text" class="form-control" aria-label="Default" name="name"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->name }}">
                                        @error('name')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.name') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white" for="country">País</label>
                                        <select class="form-select" aria-label="Default select example" name="country">
                                            @if ($user->country)
                                                <option selected value="{{ $user->country->id }}">{{ $user->country->name }}
                                                </option>
                                            @else
                                                <option selected>Elige una opción</option>
                                            @endif

                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.country') }}</span>
                                        @enderror
                                    </div>

                                    <div class="row " style="margin-right:10px">
                                        <div class="mb-3 col-3 offset-2">
                                            <label class="block mb-2 text-sm font-medium text-white"
                                                for="area">Teléfono</label>
                                            <select class="form-select" aria-label="Default select example" name="area">
                                                @if ($user->area)
                                                    <option selected value="{{ $user->area }}">{{ $user->area }}
                                                    </option>
                                                @else
                                                    <option selected value="">Elige una opción</option>
                                                @endif
                                                <option value="+1">+1 (🇨🇦)</option>
                                                <option value="+1">+1 (🇺🇸)</option>
                                                <option value="+7">+7 (🇰🇿)</option>
                                                <option value="+7">+7 (🇷🇺)</option>
                                                <option value="+20">+20 (🇪🇬)</option>
                                                <option value="+27">+27 (🇿🇦)</option>
                                                <option value="+30">+30 (🇬🇷)</option>
                                                <option value="+31">+31 (🇳🇱)</option>
                                                <option value="+32">+32 (🇧🇪)</option>
                                                <option value="+33">+33 (🇫🇷)</option>
                                                <option value="+34">+34 (🇪🇸)</option>
                                                <option value="+36">+36 (🇭🇺)</option>
                                                <option value="+39">+39 (🇮🇹)</option>
                                                <option value="+40">+40 (🇷🇴)</option>
                                                <option value="+41">+41 (🇨🇭)</option>
                                                <option value="+43">+43 (🇦🇹)</option>
                                                <option value="+44">+44 (🇬🇬)</option>
                                                <option value="+44">+44 (🇮🇲)</option>
                                                <option value="+44">+44 (🇯🇪)</option>
                                                <option value="+44">+44 (🇬🇧)</option>
                                                <option value="+45">+45 (🇩🇰)</option>
                                                <option value="+46">+46 (🇸🇪)</option>
                                                <option value="+47">+47 (🇧🇻)</option>
                                                <option value="+47">+47 (🇳🇴)</option>
                                                <option value="+47">+47 (🇸🇯)</option>
                                                <option value="+48">+48 (🇵🇱)</option>
                                                <option value="+49">+49 (🇩🇪)</option>
                                                <option value="+51">+51 (🇵🇪)</option>
                                                <option value="+52">+52 (🇲🇽)</option>
                                                <option value="+53">+53 (🇨🇺)</option>
                                                <option value="+54">+54 (🇦🇷)</option>
                                                <option value="+55">+55 (🇧🇷)</option>
                                                <option value="+56">+56 (🇨🇱)</option>
                                                <option value="+57">+57 (🇨🇴)</option>
                                                <option value="+58">+58 (🇻🇪)</option>
                                                <option value="+60">+60 (🇲🇾)</option>
                                                <option value="+61">+61 (🇦🇺)</option>
                                                <option value="+61">+61 (🇨🇽)</option>
                                                <option value="+61">+61 (🇨🇨)</option>
                                                <option value="+62">+62 (🇮🇩)</option>
                                                <option value="+63">+63 (🇵🇭)</option>
                                                <option value="+64">+64 (🇳🇿)</option>
                                                <option value="+64">+64 (🇵🇳)</option>
                                                <option value="+65">+65 (🇸🇬)</option>
                                                <option value="+66">+66 (🇹🇭)</option>
                                                <option value="+81">+81 (🇯🇵)</option>
                                                <option value="+82">+82 (🇰🇷)</option>
                                                <option value="+84">+84 (🇻🇳)</option>
                                                <option value="+86">+86 (🇨🇳)</option>
                                                <option value="+90">+90 (🇹🇷)</option>
                                                <option value="+91">+91 (🇮🇳)</option>
                                                <option value="+92">+92 (🇵🇰)</option>
                                                <option value="+93">+93 (🇦🇫)</option>
                                                <option value="+94">+94 (🇱🇰)</option>
                                                <option value="+95">+95 (🇲🇲)</option>
                                                <option value="+98">+98 (🇮🇷)</option>
                                                <option value="+211">+211 (🇸🇸)</option>
                                                <option value="+212">+212 (🇲🇦)</option>
                                                <option value="+213">+213 (🇩🇿)</option>
                                                <option value="+216">+216 (🇹🇳)</option>
                                                <option value="+218">+218 (🇱🇾)</option>
                                                <option value="+220">+220 (🇬🇲)</option>
                                                <option value="+221">+221 (🇸🇳)</option>
                                                <option value="+222">+222 (🇲🇷)</option>
                                                <option value="+223">+223 (🇲🇱)</option>
                                                <option value="+224">+224 (🇬🇳)</option>
                                                <option value="+225">+225 (🇨🇮)</option>
                                                <option value="+226">+226 (🇧🇫)</option>
                                                <option value="+227">+227 (🇳🇪)</option>
                                                <option value="+228">+228 (🇹🇬)</option>
                                                <option value="+229">+229 (🇧🇯)</option>
                                                <option value="+230">+230 (🇲🇺)</option>
                                                <option value="+231">+231 (🇱🇷)</option>
                                                <option value="+232">+232 (🇸🇱)</option>
                                                <option value="+233">+233 (🇬🇭)</option>
                                                <option value="+234">+234 (🇳🇬)</option>
                                                <option value="+235">+235 (🇹🇩)</option>
                                                <option value="+236">+236 (🇨🇫)</option>
                                                <option value="+237">+237 (🇨🇲)</option>
                                                <option value="+238">+238 (🇨🇻)</option>
                                                <option value="+239">+239 (🇸🇹)</option>
                                                <option value="+240">+240 (🇬🇶)</option>
                                                <option value="+241">+241 (🇬🇦)</option>
                                                <option value="+242">+242 (🇨🇬)</option>
                                                <option value="+243">+243 (🇨🇩)</option>
                                                <option value="+244">+244 (🇦🇴)</option>
                                                <option value="+245">+245 (🇬🇼)</option>
                                                <option value="+246">+246 (🇮🇴)</option>
                                                <option value="+248">+248 (🇸🇨)</option>
                                                <option value="+249">+249 (🇸🇩)</option>
                                                <option value="+250">+250 (🇷🇼)</option>
                                                <option value="+251">+251 (🇪🇹)</option>
                                                <option value="+252">+252 (🇸🇴)</option>
                                                <option value="+253">+253 (🇩🇯)</option>
                                                <option value="+254">+254 (🇰🇪)</option>
                                                <option value="+255">+255 (🇹🇿)</option>
                                                <option value="+256">+256 (🇺🇬)</option>
                                                <option value="+257">+257 (🇧🇮)</option>
                                                <option value="+258">+258 (🇲🇿)</option>
                                                <option value="+260">+260 (🇿🇲)</option>
                                                <option value="+261">+261 (🇲🇬)</option>
                                                <option value="+262">+262 (🇹🇫)</option>
                                                <option value="+262">+262 (🇾🇹)</option>
                                                <option value="+262">+262 (🇷🇪)</option>
                                                <option value="+263">+263 (🇿🇼)</option>
                                                <option value="+264">+264 (🇳🇦)</option>
                                                <option value="+265">+265 (🇲🇼)</option>
                                                <option value="+266">+266 (🇱🇸)</option>
                                                <option value="+267">+267 (🇧🇼)</option>
                                                <option value="+268">+268 (🇸🇿)</option>
                                                <option value="+269">+269 (🇰🇲)</option>
                                                <option value="+290">+290 (🇸🇭)</option>
                                                <option value="+291">+291 (🇪🇷)</option>
                                                <option value="+297">+297 (🇦🇼)</option>
                                                <option value="+298">+298 (🇫🇴)</option>
                                                <option value="+299">+299 (🇬🇱)</option>
                                                <option value="+345">+345 (🇰🇾)</option>
                                                <option value="+350">+350 (🇬🇮)</option>
                                                <option value="+351">+351 (🇵🇹)</option>
                                                <option value="+352">+352 (🇱🇺)</option>
                                                <option value="+353">+353 (🇮🇪)</option>
                                                <option value="+354">+354 (🇮🇸)</option>
                                                <option value="+355">+355 (🇦🇱)</option>
                                                <option value="+356">+356 (🇲🇹)</option>
                                                <option value="+357">+357 (🇨🇾)</option>
                                                <option value="+358">+358 (🇦🇽)</option>
                                                <option value="+358">+358 (🇫🇮)</option>
                                                <option value="+359">+359 (🇧🇬)</option>
                                                <option value="+370">+370 (🇱🇹)</option>
                                                <option value="+371">+371 (🇱🇻)</option>
                                                <option value="+372">+372 (🇪🇪)</option>
                                                <option value="+373">+373 (🇲🇩)</option>
                                                <option value="+374">+374 (🇦🇲)</option>
                                                <option value="+375">+375 (🇧🇾)</option>
                                                <option value="+376">+376 (🇦🇩)</option>
                                                <option value="+377">+377 (🇲🇨)</option>
                                                <option value="+378">+378 (🇸🇲)</option>
                                                <option value="+379">+379 (🇻🇦)</option>
                                                <option value="+380">+380 (🇺🇦)</option>
                                                <option value="+381">+381 (🇷🇸)</option>
                                                <option value="+382">+382 (🇲🇪)</option>
                                                <option value="+383">+383 (🇽🇰)</option>
                                                <option value="+385">+385 (🇭🇷)</option>
                                                <option value="+386">+386 (🇸🇮)</option>
                                                <option value="+387">+387 (🇧🇦)</option>
                                                <option value="+389">+389 (🇲🇰)</option>
                                                <option value="+420">+420 (🇨🇿)</option>
                                                <option value="+421">+421 (🇸🇰)</option>
                                                <option value="+423">+423 (🇱🇮)</option>
                                                <option value="+500">+500 (🇫🇰)</option>
                                                <option value="+500">+500 (🇬🇸)</option>
                                                <option value="+501">+501 (🇧🇿)</option>
                                                <option value="+502">+502 (🇬🇹)</option>
                                                <option value="+503">+503 (🇸🇻)</option>
                                                <option value="+504">+504 (🇭🇳)</option>
                                                <option value="+505">+505 (🇳🇮)</option>
                                                <option value="+506">+506 (🇨🇷)</option>
                                                <option value="+507">+507 (🇵🇦)</option>
                                                <option value="+508">+508 (🇵🇲)</option>
                                                <option value="+509">+509 (🇭🇹)</option>
                                                <option value="+590">+590 (🇬🇵)</option>
                                                <option value="+590">+590 (🇧🇱)</option>
                                                <option value="+590">+590 (🇲🇫)</option>
                                                <option value="+591">+591 (🇧🇴)</option>
                                                <option value="+592">+592 (🇬🇾)</option>
                                                <option value="+593">+593 (🇪🇨)</option>
                                                <option value="+594">+594 (🇬🇫)</option>
                                                <option value="+595">+595 (🇵🇾)</option>
                                                <option value="+596">+596 (🇲🇶)</option>
                                                <option value="+597">+597 (🇸🇷)</option>
                                                <option value="+598">+598 (🇺🇾)</option>
                                                <option value="+599">+599 ()</option>
                                                <option value="+670">+670 (🇹🇱)</option>
                                                <option value="+672">+672 (🇦🇶)</option>
                                                <option value="+672">+672 (🇭🇲)</option>
                                                <option value="+672">+672 (🇳🇫)</option>
                                                <option value="+673">+673 (🇧🇳)</option>
                                                <option value="+674">+674 (🇳🇷)</option>
                                                <option value="+675">+675 (🇵🇬)</option>
                                                <option value="+676">+676 (🇹🇴)</option>
                                                <option value="+677">+677 (🇸🇧)</option>
                                                <option value="+678">+678 (🇻🇺)</option>
                                                <option value="+679">+679 (🇫🇯)</option>
                                                <option value="+680">+680 (🇵🇼)</option>
                                                <option value="+681">+681 (🇼🇫)</option>
                                                <option value="+682">+682 (🇨🇰)</option>
                                                <option value="+683">+683 (🇳🇺)</option>
                                                <option value="+685">+685 (🇼🇸)</option>
                                                <option value="+686">+686 (🇰🇮)</option>
                                                <option value="+687">+687 (🇳🇨)</option>
                                                <option value="+688">+688 (🇹🇻)</option>
                                                <option value="+689">+689 (🇵🇫)</option>
                                                <option value="+690">+690 (🇹🇰)</option>
                                                <option value="+691">+691 (🇫🇲)</option>
                                                <option value="+692">+692 (🇲🇭)</option>
                                                <option value="+850">+850 (🇰🇵)</option>
                                                <option value="+852">+852 (🇭🇰)</option>
                                                <option value="+853">+853 (🇲🇴)</option>
                                                <option value="+855">+855 (🇰🇭)</option>
                                                <option value="+856">+856 (🇱🇦)</option>
                                                <option value="+880">+880 (🇧🇩)</option>
                                                <option value="+886">+886 (🇹🇼)</option>
                                                <option value="+960">+960 (🇲🇻)</option>
                                                <option value="+961">+961 (🇱🇧)</option>
                                                <option value="+962">+962 (🇯🇴)</option>
                                                <option value="+963">+963 (🇸🇾)</option>
                                                <option value="+964">+964 (🇮🇶)</option>
                                                <option value="+965">+965 (🇰🇼)</option>
                                                <option value="+966">+966 (🇸🇦)</option>
                                                <option value="+967">+967 (🇾🇪)</option>
                                                <option value="+968">+968 (🇴🇲)</option>
                                                <option value="+970">+970 (🇵🇸)</option>
                                                <option value="+971">+971 (🇦🇪)</option>
                                                <option value="+972">+972 (🇮🇱)</option>
                                                <option value="+973">+973 (🇧🇭)</option>
                                                <option value="+974">+974 (🇶🇦)</option>
                                                <option value="+975">+975 (🇧🇹)</option>
                                                <option value="+976">+976 (🇲🇳)</option>
                                                <option value="+977">+977 (🇳🇵)</option>
                                                <option value="+992">+992 (🇹🇯)</option>
                                                <option value="+993">+993 (🇹🇲)</option>
                                                <option value="+994">+994 (🇦🇿)</option>
                                                <option value="+995">+995 (🇬🇪)</option>
                                                <option value="+996">+996 (🇰🇬)</option>
                                                <option value="+998">+998 (🇺🇿)</option>
                                                <option value="+1242">+1242 (🇧🇸)</option>
                                                <option value="+1246">+1246 (🇧🇧)</option>
                                                <option value="+1264">+1264 (🇦🇮)</option>
                                                <option value="+1268">+1268 (🇦🇬)</option>
                                                <option value="+1284">+1284 (🇻🇬)</option>
                                                <option value="+1340">+1340 (🇻🇮)</option>
                                                <option value="+1441">+1441 (🇧🇲)</option>
                                                <option value="+1473">+1473 (🇬🇩)</option>
                                                <option value="+1649">+1649 (🇹🇨)</option>
                                                <option value="+1664">+1664 (🇲🇸)</option>
                                                <option value="+1670">+1670 (🇲🇵)</option>
                                                <option value="+1671">+1671 (🇬🇺)</option>
                                                <option value="+1684">+1684 (🇦🇸)</option>
                                                <option value="+1758">+1758 (🇱🇨)</option>
                                                <option value="+1767">+1767 (🇩🇲)</option>
                                                <option value="+1784">+1784 (🇻🇨)</option>
                                                <option value="+1849">+1849 (🇩🇴)</option>
                                                <option value="+1868">+1868 (🇹🇹)</option>
                                                <option value="+1869">+1869 (🇰🇳)</option>
                                                <option value="+1876">+1876 (🇯🇲)</option>
                                                <option value="+1939">+1939 (🇵🇷)</option>
                                            </select>
                                            @error('area')
                                                <span
                                                    class="text-danger position-absolute">{{ trans('user.create.fields.area') }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4">
                                            <label class="block mb-2 text-sm font-medium text-white" for="phone"
                                                style="visibility: hidden">s</label>
                                            <input type="text" class="form-control" aria-label="Default"
                                                name="phone" aria-describedby="inputGroup-sizing-default"
                                                value="{{ $user->phone }}">
                                            @error('phone')
                                                <span
                                                    class="text-danger position-absolute">{{ trans('user.create.fields.phone') }}</span>
                                            @enderror

                                        </div>
                                    </div>


                                    <div class="mb-3 col-8 ">
                                        <label class="block mb-2 text-sm font-medium text-white" for="timezone">Zona
                                            Horaria</label>
                                        <select class="form-select" aria-label="Default select example" name="timezone">
                                            @if ($user->time_zone)
                                                <option value="{{ $user->time_zone }}">{{ $user->time_zone }}
                                                </option>
                                            @else
                                                <option selected value="{{ null }}">Elige una opción</option>
                                            @endif
                                            @foreach ($timezone as $key => $time)
                                                <option class="false" data-offset="0" value="{{ $key }}">
                                                    {{ $key }}</option>
                                            @endforeach

                                        </select>
                                        @error('timezone')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.timezone') }}</span>
                                        @enderror

                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white"
                                            for="description">Descripción</label>
                                        <textarea class="form-control" name="description" placeholder="..." value="{{ $user->description }}" id="floatingTextarea">{{ $user->description }}</textarea>
                                        @error('description')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.description') }}</span>
                                        @enderror
                                    </div>
                                    <div class="p-0 col-11" style="padding: 0">
                                        <h5 class="mt-2 text-center card-title text-light">Redes Sociales</h5>
                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white"
                                            for="channel"><i class="bi bi-instagram" style="font-size: 1.4em"></i></label>
                                        <input type="url" class="form-control" aria-label="Default" name="instagram" placeholder="https://example.com"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->instagram }}">
                                        @error('instagram')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.channel') }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white"
                                            for="channel"><i class="bi bi-facebook" style="font-size: 1.4em"></i></label>
                                        <input type="url" class="form-control" aria-label="Default" name="facebook" placeholder="https://example.com"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->facebook }}">
                                        @error('facebook')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.channel') }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-3 col-8">
                                        <label class="block mb-2 text-sm font-medium text-white"
                                            for="channel"><i class="bi bi-youtube" style="font-size: 1.4em"></i></label>
                                        <input type="url" class="form-control" aria-label="Default" name="youtube" placeholder="https://example.com"
                                            aria-describedby="inputGroup-sizing-default" value="{{ $user->youtube }}">
                                        @error('youtube')
                                            <span
                                                class="text-danger position-absolute">{{ trans('user.create.fields.channel') }}</span>
                                        @enderror
                                    </div>
                                    

                                    <div class="mt-2 mb-4 col-6" style="display: block;margin-right:105px">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        {{-- <a href="{{ route('summary') }}"><button type="button" class="btn btn-danger">Volver</button></a> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <input type="text" class="form-control" aria-label="Default" name="times" id="times"
                    aria-describedby="inputGroup-sizing-default" value="{{ $times }}" style="display: none"> --}}
            @endif
        </div>
    </div>
    @include('layouts.footer')
@endsection
@push('chatters')
    {{-- @if (env('APP_ENV') == 'local')
        <script src="{{ asset('/js/setTime.js') }}"></script>
    @else
        <script src="./public/js/setTime.js"></script>
    @endif --}}
@endpush
