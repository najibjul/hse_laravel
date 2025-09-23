<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">DESKRIPSI</th>
            <th rowspan="2">KATEGORI</th>
            <th rowspan="2">RANK</th>
            <th rowspan="2">AREA</th>
            <th colspan="2">GAMBAR</th>
            <th rowspan="2">REKOMENDASI</th>
            <th rowspan="2">PIC</th>
            <th rowspan="2">BATAS WAKTU PENYELESAIAN</th>
            <th rowspan="2">STATUS</th>
        </tr>
        <tr>
            <th>BEFORE</th>
            <th>AFTER</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><?php echo e($dailyCheck->qrpDetail?->description); ?></td>
            <td><?php echo e($dailyCheck->qrpDetail->category->category_name); ?></td>
            <td><?php echo e($dailyCheck->qrpDetail->rank->rank_name); ?></td>
            <td><?php echo e($dailyCheck->area); ?></td>
            <td></td>
            <td></td>
            <td>
                <ul>
                    <?php $__currentLoopData = $dailyCheck->qrpDetail->qrpRecomendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qrpRecomendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($qrpRecomendation->user->name); ?> (<?php echo e($qrpRecomendation->user->nip); ?>) - <?php echo e($qrpRecomendation->recomendation); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </td>
            <td></td>
            <td><?php echo e(\Carbon\Carbon::parse($dailyCheck->qrpDetail->due_date)->format('d M Y')); ?></td>
            <td>
                <?php echo e($dailyCheck->qrpDetail->qrpStatus->name); ?>

            </td>
        </tr>
    </tbody>
</table><?php /**PATH C:\laragon\www\hse\resources\views/export/qrp.blade.php ENDPATH**/ ?>