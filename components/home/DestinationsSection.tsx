import { Box, Container, Heading, SimpleGrid, Text, VStack } from "@chakra-ui/react";

const destinations = [
  "Antigua Guatemala",
  "Panajachel",
  "Lake Atitlán",
  "Semuc Champey",
  "Puerto San José",
  "Guatemala City",
];

export default function DestinationsSection() {
  return (
    <Box py={20} bg="gray.50">
      <Container maxW="7xl">
        <VStack align="stretch" gap={8}>
          <Heading>Destinos populares</Heading>

          <SimpleGrid columns={{ base: 1, sm: 2, lg: 3 }} gap={6}>
            {destinations.map((item) => (
              <Box key={item} h="220px" rounded="2xl" bg="white" borderWidth="1px" p={6}>
                <Text fontWeight="bold" fontSize="lg">
                  {item}
                </Text>
              </Box>
            ))}
          </SimpleGrid>
        </VStack>
      </Container>
    </Box>
  );
}
