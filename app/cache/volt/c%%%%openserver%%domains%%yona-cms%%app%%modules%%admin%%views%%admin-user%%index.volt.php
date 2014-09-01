<p><a href="/admin/admin-user/add" class="ui positive button"><i class="add icon"></i> <?php echo $this->helper->translate('Add'); ?></a></p>

<table class="ui table segment">
    <thead>
        <tr>
            <th><?php echo $this->helper->translate('Edit'); ?></th>
            <th><?php echo $this->helper->translate('Login'); ?></th>
            <th><?php echo $this->helper->translate('Email'); ?></th>
            <th><?php echo $this->helper->translate('Active'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entries as $user) { ?>
        <tr>
            <td><a href="/admin/admin-user/edit/<?php echo $user->getId(); ?>" class="mini ui icon button"><i class="pencil icon"></i></a></td>
            <td><?php echo $user->getLogin(); ?></td>
            <td><?php echo $user->getEmail(); ?></td>
            <td><?php if ($user->getActive()) { ?><i class="checkmark icon"></i><?php } ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>