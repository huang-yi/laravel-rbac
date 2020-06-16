@role('r1')
r1
@elserole('r2')
r2
@endrole

@notrole('r3')
r3
@elsenotrole('r4')
r4
@endnotrole

@roles('r5&r6')
r5&r6
@elseroles('r7&r8')
r7&r8
@endrole

@anyroles('r9|ra')
r9|ra
@elseanyroles('rb|rc')
rb|rc
@endrole
