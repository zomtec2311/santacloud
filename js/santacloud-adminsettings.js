window.addEventListener('DOMContentLoaded', function () {
    {
        const wtpara_test = document.getElementById('wtpara_test1');
        wtpara_test.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/test'),
                {
                    wtpara_test: 1
                }
            );
        }
    }
    {
        const wtpara_test = document.getElementById('wtpara_test2');
        wtpara_test.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/test'),
                {
                    wtpara_test: 2
                }
            );
        }
    }
    {
        const wtpara_last = document.getElementById('wtpara_last1');
        wtpara_last.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/last'),
                {
                    wtpara_last: 1
                }
            );
        }
    }
    {
        const wtpara_last = document.getElementById('wtpara_last2');
        wtpara_last.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/last'),
                {
                    wtpara_last: 2
                }
            );
        }
    }
    {
        const wtpara_lock = document.getElementById('wtpara_lock1');
        wtpara_lock.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/lock'),
                {
                    wtpara_lock: 1
                }
            );
        }
    }
    {
        const wtpara_lock = document.getElementById('wtpara_lock2');
        wtpara_lock.onchange = function () {
            $.post(
                OC.generateUrl('/apps/santacloud/ajax/lock'),
                {
                    wtpara_lock: 2
                }
            );
        }
    }
});
