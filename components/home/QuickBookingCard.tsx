"use client";

import { Button, Card, Field, Input, Stack } from "@chakra-ui/react";

export default function QuickBookingCard() {
  return (
    <Card.Root rounded="2xl" shadow="2xl" bg="white">
      <Card.Body>
        <Stack gap={4}>
          <Field.Root>
            <Field.Label>Origen</Field.Label>
            <Input placeholder="Aeropuerto La Aurora" />
          </Field.Root>

          <Field.Root>
            <Field.Label>Destino</Field.Label>
            <Input placeholder="Antigua Guatemala" />
          </Field.Root>

          <Field.Root>
            <Field.Label>Fecha</Field.Label>
            <Input type="date" />
          </Field.Root>

          <Field.Root>
            <Field.Label>Pasajeros</Field.Label>
            <Input type="number" min={1} defaultValue={1} />
          </Field.Root>

          <Button colorScheme="blue" size="lg" rounded="xl">
            Buscar traslado
          </Button>
        </Stack>
      </Card.Body>
    </Card.Root>
  );
}





