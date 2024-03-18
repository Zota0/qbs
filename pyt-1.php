<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="strict-origin" />
    <title>Document</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js'></script>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-700 min-h-svh h-svh max-h-svh px-12'>
    <?php
        $NazwaPelna = basename(__FILE__);
        $NazwaBezKropki = str_replace(('.php'),'', $NazwaPelna);
        $NrPyt = str_replace('pyt-','',$NazwaBezKropki);

        $SciezkaDoPlikuPytan = __DIR__.'/pytania.json';

        $OwtorzPlikPytan = fopen($SciezkaDoPlikuPytan,'r+');
        $CzytajPlikPytan = fread($OwtorzPlikPytan,filesize($SciezkaDoPlikuPytan));

        $PlikPytanJakoTablica = json_decode($CzytajPlikPytan,true);

        (string)$WinnerPage = $PlikPytanJakoTablica['winner_page'];
        (string)$AvailableAudience = $PlikPytanJakoTablica['publicznosc'];
        (string)$AvailableFityFifty = $PlikPytanJakoTablica['polowa'];
        (string)$AvailablePhone = $PlikPytanJakoTablica['telefon'];


        (array)$PlikPytan = $PlikPytanJakoTablica[$NrPyt];

        (string)$Pytanie = $PlikPytan['pytanie'];
        (string)$Odpowiedz_A = $PlikPytan['A'];
        (string)$Odpowiedz_B = $PlikPytan['B'];
        (string)$Odpowiedz_C = $PlikPytan['C'];
        (string)$Odpowiedz_D = $PlikPytan['D'];
        (string)$PoprawnaOdp = $PlikPytan['poprawna_odp'];

        (int)$LiczbaPytan = $PlikPytanJakoTablica['liczba_pytan'];

        $Odpowiedzi = [
            'A' => $Odpowiedz_A,
            'B' => $Odpowiedz_B,
            'C' => $Odpowiedz_C,
            'D' => $Odpowiedz_D
        ];

        function Question($pyt) {
            echo $pyt;
        }
    
        function Answer($which_one, $Odpowiedzi) {
            $nr_odp= strtolower($which_one);
            switch($nr_odp) {
                case 'a':
                    echo $Odpowiedzi['A'];
                    break;
                case 'b':
                    echo $Odpowiedzi['B'];
                    break;
                case 'c':
                    echo $Odpowiedzi['C'];
                    break;
                case 'd':
                    echo $Odpowiedzi['D'];
                    break;
            }
        }

    ?>
    
    <div class='grid grid-rows-11 border-0 p-8 gap-0'>
        <div class='grid-row-span-5 w-full h-full border-8 border-gray-600 gap-0'>
            <div class='w-full h-full border-gray-700 border-8 align-middle font-bold bg-gray-600 p-8'>
                <p id='question' class='block text-white text-center font-bold text-5xl p-8 w-full h-full'>
                    <span class='mx-4'></span>
                    <?php Question($Pytanie);?>
                </p>
            </div>
        </div>
        <div class='max-w-full grid-row-span-1 w-full h-min border-gray-600 border-8 bg-gray-600 overflow-hidden -mb-8'>
            <center class='grid grid-cols-3 grid-rows-1 border-gray-700 border-8 align-middle font-bold bg-gray-600 p-8'>
                <div class="p-0 font-bold kolo-ratunkowe">
                    <button accesskey='z' class='hover:cursor-pointer' onclick='Helper(this)' id="ratunek-publicznosc">
                        <img width='75' class='scale-100' src='audience.png'>
                    </button>
                </div>
                <div class="p-0 font-bold kolo-ratunkowe">
                    <button accesskey='x' class='hover:cursor-pointer' onclick='Helper(this)' id="ratunek-50_50">
                        <img width='75' class='scale-100' src='50-percent.png'>
                    </button>
                </div>
                <div class="p-0 font-bold kolo-ratunkowe">
                    <button accesskey='c' class='hover:cursor-pointer' onclick='Helper(this)' id="ratunek-telefon">
                        <img width='75' class='scale-100' src='call.png'>
                    </button>
                </div>
            </center>
        </div>
        <div class='relative -top-44  mt-0 h-full w-full grid-row-span-5 grid border-8 gap-0 border-gray-600 bg-gray-600'>
            <div class='h-full w-full grid gap-10 grid-rows-2 grid-cols-2 p-8 border-8 border-gray-700 bg-gray-600'>
                <button value='A' id='answer-a' class='answer-button border-pink-900 bg-red-600 border-8 p-8 font-bold font-xs text-red-100 w-11/12 h-full' accesskey='1' onclick='CheckAnswer(this)'>A. <?php Answer('A',$Odpowiedzi);?></button>
                <button value='B' id='answer-b' class='answer-button border-cyan-900 bg-blue-600 border-8 p-8 font-bold font-xs text-blue-100 w-11/12 h-full' accesskey='2' onclick='CheckAnswer(this)'>B. <?php Answer('B',$Odpowiedzi);?></button>
                <button value='C' id='answer-c' class='answer-button border-violet-900 bg-violet-600 border-8 p-8 font-bold font-xs text-purple-100 w-11/12 h-full' accesskey='3' onclick='CheckAnswer(this)'>C. <?php Answer('C',$Odpowiedzi);?></button>
                <button value='D' id='answer-d' class='answer-button border-yellow-900 bg-orange-600 border-8 p-8 font-bold font-xs text-orange-100 w-11/12 h-full' accesskey='4' onclick='CheckAnswer(this)'>D. <?php Answer('D',$Odpowiedzi);?></button>
            </div>
        </div>
    </div>

    <script>
        const fs = require('fs');
        

        var available_FiftyFifty = <?php echo $AvailableFityFifty; ?>;
        var available_Phone = <?php echo $AvailablePhone; ?>;
        var available_Audience = <?php echo $AvailableAudience; ?>;

        async function RetriveJSONData(file) {
            try {
                const Response = await fetch(file);
                const Data = await Response.json();
                console.log(Data);
                return Data;
            } catch(error) {
                console.error(error);
            }
        }

        async function ChangeJSONData(json, which, new_value) {
            try {
                const Data = json;
                Data[which] = new_value;
                const ModifiedJSON = JSON.stringify(Data);
                console.log(ModifiedJSON);
                return ModifiedJSON;
            } catch(error) {
                console.error("Error " + error);
                return json;
            }
        }

        var PytaniaJSON = RetriveJSONData('./pytania.json');

        var last_question = <?php echo $LiczbaPytan;?>;
        var now_question = <?php echo $NrPyt; ?>;

        function GetAvailable() {
            return [available_FiftyFifty, available_Phone, available_Audience];
        }

        if(!available_Audience) {
            const a_a = document.getElementById('ratunek-publicznosc');
            a_a.disabled = true;
            a_a.classList.add('grayscale');
        }

        if(!available_Phone) {
            const a_p = document.getElementById('ratunek-publicznosc');
            a_p.disabled = true;
            a_p.classList.add('grayscale');
        }

        if(!available_FiftyFifty) {
            const a_ff = document.getElementById('ratunek-publicznosc');
            a_ff.disabled = true;
            a_ff.classList.add('grayscale');
        }

        console.log(last_question);
        console.log(now_question);

        async function Helper(what) {
            what.classList.add('grayscale');
            switch(what.id) {
                case 'ratunek-telefon': 
                    console.log('telefon');
                    what.disabled = true;
                    PytaniaJSON = ChangeJSONData(await PytaniaJSON,"telefon","false");
                    available_Phone = false;
                    break;
                case 'ratunek-publicznosc':
                    console.log('publicznosc');
                    what.disabled = true;
                    PytaniaJSON = ChangeJSONData(await PytaniaJSON,"publicznosc","false");
                    available_Audience = false;
                    break;
                case 'ratunek-50_50':
                    console.log('50/50');
                    what.disabled = true;
                    PytaniaJSON = ChangeJSONData(await PytaniaJSON,"polowa","false");
                    available_FiftyFifty = false;
                    break;
            }
            
            
            console.log(what.id);
        }

        async function CheckAnswer(what) {

            const answer_buttons = document.getElementsByClassName('answer-button');
            var clicked_btn = what;
            var right_clicked_btn;

            if(what.value == '<?php echo $PoprawnaOdp; ?>') {
                console.log("BRAWO!");
                right_clicked_btn = true;
            } else {
                console.log("ŹlE!");
                right_clicked_btn = false;
            }

            for (const answer_btn of answer_buttons) {
                answer_btn.disabled = true;
                if(answer_btn !== clicked_btn) {
                    answer_btn.style.backgroundColor = 'dimgray';
                    answer_btn.style.borderColor = 'grey';
                }
            }
    
            what.classList.add('animate-pulse');
            await StressWait('time_to_reveal.wav');
            clicked_btn.classList.remove('animate-pulse');

            if(right_clicked_btn) {
                clicked_btn.style.backgroundColor = 'green';
                clicked_btn.classList.add('brightness-150');
                clicked_btn.style.borderColor = 'lime';
                PlayAudio('right_answer.wav');
                
                if(now_question === last_question) {

                    const win_page = "<?php echo './'.$WinnerPage; ?>";
                    console.log(win_page);
                    window.location.href = win_page;

                } else {

                    const next_question = './pyt-' +  ++now_question + '.php';
                    console.log(next_question);
                    window.location.href = next_question;

                }

            } else {            
                clicked_btn.style.backgroundColor = '#FF1717';
                clicked_btn.classList.add('brightness-150');
                clicked_btn.style.borderColor = '#711111';
                PlayAudio('bad_answer.wav');
            }
        }

        async function Wait(time_in_ms = 2500) {
            await new Promise(resolve => setTimeout(resolve, time_in_ms));
        }

        async function PlayAudio(file) {
            var audio = new Audio(file);
            return audio.play();
        }

        async function StressWait(audio_file) {
            PlayAudio(audio_file);
            await Wait(4800);
        }
    </script>
</body>
</html>