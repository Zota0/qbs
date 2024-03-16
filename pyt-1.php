<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' integrity='sha512-tx5+1LWHez1QiaXlAyDwzdBTfDjX07GMapQoFTS74wkcPMsI3So0KYmFe6EHZjI8+eSG0ljBlAQc3PQ5BTaZtQ==' crossorigin='anonymous'/>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js' integrity='sha512-HAXr8ULpyrhyIF0miP+mFTwOagNI+UVA38US1XdtBbkU7mse59ar0ck4KBil/jyzkTO37DWLfRQvEeUWgwHu0g==' crossorigin='anonymous'></script>
</head>
<body class='bg-gray-700 min-h-svh h-svh max-h-svh'>
    <?php
        $NazwaPelna = basename(__FILE__);
        $NazwaBezKropki = str_replace(('.php'),'', $NazwaPelna);
        $NrPyt = str_replace('pyt-','',$NazwaBezKropki);

        $SciezkaDoPlikuPytan = __DIR__.'/pytania.json';;
        $PlikPytanJakoTablica = json_decode(fread(fopen($SciezkaDoPlikuPytan,'r'),filesize($SciezkaDoPlikuPytan)), true);

        $PlikPytan = $PlikPytanJakoTablica[$NrPyt];
        (string)$WinnerPage = $PlikPytanJakoTablica['winner_page'];

        $Pytanie = $PlikPytan['pytanie'];

        $Odpowiedz_A = $PlikPytan['A'];
        $Odpowiedz_B = $PlikPytan['B'];
        $Odpowiedz_C = $PlikPytan['C'];
        $Odpowiedz_D = $PlikPytan['D'];
        $PoprawnaOdp = $PlikPytan['poprawna_odp'];

        $LiczbaPytan = $PlikPytanJakoTablica['liczba_pytan'];

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
    
    <div class='grid grid-rows-11 border-0 p-4 px-12'>
        <div class='grid-row-span-5 w-full h-full border-8 border-gray-600'>
            <div class='w-full h-full border-gray-700 border-8 align-middle font-bold bg-gray-600 p-8'>
                <h1 id='question' class='block text-white text-center font-bold text-5xl p-8 w-full h-full'>
                    <span class='mx-4'></span>
                    <?php Question($Pytanie);?>
                </h1>
            </div>
        </div>
        <div class='grid-row-span-1 w-full h-full border-gray-600 border-8 bg-gray-600'>
            <div class="w-full h-full grid grid-cols-3 grid-rows-1 border-gray-700 border-8 align-middle font-bold bg-gray-600 p-8">
                <div class="border-8 border-amber-900 p-8 font-bold font-xs bg-amber-700 kolo-ratunkowe" id="ratunek-publicznosc">

                </div>
                <div class="border-8 border-rose-900 p-8 font-bold font-xs bg-rose-500 kolo-ratunkowe" id="ratunek-50/50">

                </div>
                <div class="border-8 border-emerald-900 p-8 font-bold font-xs bg-emerald-700 kolo-ratunkowe" id="ratunek-telefon">

                </div>
            </div>
        </div>
        <div class='grid-row-span-5 grid grid-rows-2 grid-cols-2 border-8 border-gray-600'>
            <button value='A' id='answer-a' class='answer-button border-pink-900 bg-red-600 border-8 p-8 font-bold font-xs text-red-100 w-full' onclick='CheckAnswer(this)'>A. <?php Answer('A',$Odpowiedzi);?></button>
            <button value='B' id='answer-b' class='answer-button border-cyan-900 bg-blue-600 border-8 p-8 font-bold font-xs text-blue-100 w-full' onclick='CheckAnswer(this)'>B. <?php Answer('B',$Odpowiedzi);?></button>
            <button value='C' id='answer-c' class='answer-button border-violet-900 bg-violet-600 border-8 p-8 font-bold font-xs text-purple-100 w-full' onclick='CheckAnswer(this)'>C. <?php Answer('C',$Odpowiedzi);?></button>
            <button value='D' id='answer-d' class='answer-button border-yellow-900 bg-orange-600 border-8 p-8 font-bold font-xs text-orange-100 w-full' onclick='CheckAnswer(this)'>D. <?php Answer('D',$Odpowiedzi);?></button>
        </div>
    </div>

    <script>
        var last_question = <?php echo $LiczbaPytan;?>;
        var now_question = <?php echo $NrPyt; ?>;

        console.log(last_question);
        console.log(now_question);

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