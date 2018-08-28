<?php
    namespace App\Models;

    use App\Core\Model;
    use App\Core\Field;
    use App\Validators\NumberValidator;
    use App\Validators\DateTimeValidator;
    use App\Validators\StringValidator;
    use App\Validators\BitValidator;

    class ReservationModel extends Model {
        protected function getFields(): array {
            return [
                'reservation_id'      => new Field((new NumberValidator())->setIntegerLength(11), false),
                'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime() , false),

                'title'           => new Field((new StringValidator())->setMaxLength(255) ),
                'description'     => new Field((new StringValidator())->setMaxLength(64*1024) ),
                'starting_price'  => new Field((new NumberValidator())->setDecimal()
                                                                       ->setUnsigned()
                                                                       ->setIntegerLength(7)
                                                                       ->setMaxDecimalDigits(2) ),
                'starts_at'       => new Field((new DateTimeValidator())->allowDate()->allowTime()),
                'ends_at'         => new Field((new DateTimeValidator())->allowDate()->allowTime()),
                'is_active'       => new Field(new BitValidator()),
                'term_id'     => new Field((new NumberValidator())->setIntegerLength(11))
            ];
        }

        public function getAllByTermId(int $termId): array {
            return $this->getAllByFieldName('term_id', $termId);
        }
    }