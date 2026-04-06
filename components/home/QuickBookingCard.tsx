"use client";

import {
  Button,
  Card,
  Field,
  Input,
  Stack,
  Text,
  Group,
  Icon
} from "@chakra-ui/react";
import { LuMapPin, LuCalendar, LuUsers, LuSearch } from "react-icons/lu";

export default function QuickBookingCard() {
  return (
    <Card.Root
      width="full"
      maxWidth="400px"
      rounded="xl"
      shadow="md"
      border="1px solid"
      borderColor="gray.100"
      bg="white"
      overflow="hidden"
    >
      <Card.Body p={6}>
        <Stack gap={5}>
          <Text textStyle="xl" fontWeight="bold" color="gray.800" mb={2}>
            ¿A dónde vamos?
          </Text>

          {/* ORIGEN */}
          <Field.Root>
            <Field.Label fontSize="xs" fontWeight="bold" textTransform="uppercase" color="gray.500">
              Origen
            </Field.Label>
            <Group width="full">
              <Input
                placeholder="Aeropuerto La Aurora (GUA)"
                variant="outline"
                size="md"
                focusRingColor="blue.500"
              />
            </Group>
          </Field.Root>

          {/* DESTINO */}
          <Field.Root>
            <Field.Label fontSize="xs" fontWeight="bold" textTransform="uppercase" color="gray.500">
              Destino
            </Field.Label>
            <Input
              placeholder="Antigua Guatemala"
              variant="outline"
              size="md"
            />
          </Field.Root>

          {/* FECHA Y PASAJEROS EN FILA */}
          <Stack direction="row" gap={4}>
            <Field.Root>
              <Field.Label fontSize="xs" fontWeight="bold" textTransform="uppercase" color="gray.500">
                Fecha
              </Field.Label>
              <Input
                type="date"
                variant="outline"
                cursor="pointer"
              />
            </Field.Root>

            <Field.Root maxWidth="120px">
              <Field.Label fontSize="xs" fontWeight="bold" textTransform="uppercase" color="gray.500">
                Pasajeros
              </Field.Label>
              <Input
                type="number"
                min={1}
                defaultValue={1}
                variant="outline"
              />
            </Field.Root>
          </Stack>

          {/* BOTÓN DE ACCIÓN */}
          <Button
            bg="blue.600"
            _hover={{ bg: "blue.700" }}
            color="white"
            size="xl"
            rounded="lg"
            mt={2}
            fontWeight="bold"
            gap={2}
          >
            <LuSearch />
            Buscar traslado
          </Button>

          <Text textAlign="center" fontSize="xs" color="gray.400">
            Cancelación gratuita en la mayoría de traslados
          </Text>
        </Stack>
      </Card.Body>
    </Card.Root>
  );
}
