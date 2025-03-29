<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            padding: 20px;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .users-table th,
        .users-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .users-table th {
            background: #262626;
            color: white;
        }

        input,
        select {
            padding: 5px;
            font-size: 14px;
        }

        .btn-save,
        .btn-delete {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-save {
            background: #5bc0de;
            color: white;
            border-radius: 5px;
        }

        .btn-delete {
            background: #d9534f;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>👥 Управление пользователями</h1>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <form action="<?php echo e(route('admin.users.update', $user->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <td><?php echo e($user->id); ?></td>
                            <td><input type="text" name="name" value="<?php echo e($user->name); ?>" required></td>
                            <td><input type="email" name="email" value="<?php echo e($user->email); ?>" required></td>
                            <td>
                                <select name="role">
                                    <option value="user" <?php echo e($user->role == 'user' ? 'selected' : ''); ?>>Пользователь</option>
                                    <option value="admin" <?php echo e($user->role == 'admin' ? 'selected' : ''); ?>>Администратор
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn-save">💾</button>
                                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST"
                                    style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-delete">🗑</button>
                                </form>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</body>

</html><?php /**PATH /var/www/html/resources/views/admin/users/users.blade.php ENDPATH**/ ?>