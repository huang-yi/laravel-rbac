@permission('p1')
p1
@elsepermission('p2')
p2
@endpermission

@notpermission('p3')
p3
@elsenotpermission('p4')
p4
@endnotpermission

@permissions('p5&p6')
p5&p6
@elsepermissions('p7&p8')
p7&p8
@endpermission

@anypermissions('p9|pa')
p9|pa
@elseanypermissions('pb|pc')
pb|pc
@endpermission
